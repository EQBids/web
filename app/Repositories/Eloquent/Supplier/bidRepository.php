<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 6/10/18
 * Time: 4:20 PM
 */

namespace App\Repositories\Eloquent\Supplier;


use App\Mail\orders\confirmedBid;
use App\Models\Buyer\Order;
use App\Models\OrderSupplier;
use App\Models\Supplier\Bid;
use App\Models\Supplier\BidItem;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\Repositories\Interfaces\Supplier\bidRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class bidRepository extends BaseRepository implements bidRepositoryInterface {

	protected  $users_repository,$supplier_repository,$settings_repository;
	public function __construct( Bid $model,UsersRepository $users_repository, supplierRepository $supplier_repository,SettingsRepository $settings_repository ) {
		parent::__construct( $model );
		$this->users_repository=$users_repository;
		$this->supplier_repository=$supplier_repository;
		$this->settings_repository=$settings_repository;
	}

	public function create( array $data ) {
		$user=Auth::user();
		if(isset($data['user_id'])){
			$user=$this->users_repository->findOneBy($data['user_id']);
			if(!$user){return false;}
		}else{
			$data['user_id']=$user->id;
		}

		if(!isset($data['order_id']) || !isset($data['supplier_id'])){
			throw new \Error('Incomplete data');
		}

		if(!$user->is_supplier){return false;}

		$orderSuppliers=OrderSupplier::query()->where('order_id',$data['order_id'])->where('supplier_id',$data['supplier_id'])->first();

		if(!$orderSuppliers){
			throw new \Error('Invalid order');
		}

		if(!$orderSuppliers->is_bidable){
			throw new \Error('Not allowed to bid');
		}

		$older_bids = Bid::query()->where('order_id',$data['order_id'])->where('supplier_id',$data['supplier_id'])->count();

		if($older_bids){
			throw new \Error('Not allowed to bid');
		}

		$amount=0;
		foreach ($data['equipments'] as $equipment){
			$amount+=$equipment['price'];
		}
		$data['amount']=$amount;
		$data['status']=Bid::STATUS_ACTIVE;
		$insurance=$this->settings_repository->getValue($user->supplier,'insurance');
		if(!$insurance || !is_numeric($insurance) || ((float)$insurance)<0 ){
			throw new \Error('Insurance value must be a positive number');
		}

		$insurance=(float)$insurance;
		$insurance/=100.0;

		$bid_items=[];
		foreach ($data['equipments'] as $key=>$equipment){
			$bid_items[$key]=[
				'price'=>$equipment['price'],
				'dropoff_fee'=>isset($equipment['pick'])?$equipment['pick']:0,
				'pickup_fee'=>isset($equipment['delivery'])?$equipment['delivery']:0,
				'insurance'=>(isset($equipment['insurance']) && $equipment['insurance'])?($insurance*$equipment['price']):0, //TODO: change to supplier settings
				'deliv_date'=>isset($equipment['from'])?$equipment['from']:null,
				'return_date'=>isset($equipment['to'])?$equipment['to']:null,
				'notes'=>isset($equipment['notes'])?$equipment['notes']:null,
				'status'=>BidItem::STATUS_DEFAULT,
				'details'=>[
					'attachments'=>isset($equipment['attachments'])?$equipment['attachments']:[],
				]
			];
		}

		DB::beginTransaction();
		$bid = Bid::create($data);
		$bid->items()->attach($bid_items);
		DB::commit();
		return $bid;
	}

	public function accesibleBids( User $user ) {
		if(!$user->is_supplier){
			return collect([]);
		}
		$order_ids = $this->supplier_repository->accesibleOrderInvitations($user);
		$order_ids=$order_ids->pluck('order_id');
		$suppliers_id = $user->suppliers()->pluck('id');
		$this->query->whereIn('order_id',$order_ids)->whereIn('supplier_id',$suppliers_id);
		$this->with(['order','user']);
		return $this->findAll(['*']);

	}

	public function updateBy( array $data, $value = null, $field = 'id' ) {
		$user=Auth::user();

		if(!$user->is_supplier){return false;}

		$bid = $this->findOneBy($value,$field);

		if(!$bid || !$bid->is_editable){
			throw new \Error('Invalid bid');
		}

		$insurance=$this->settings_repository->getValue($user->supplier,'insurance');
		if(!$insurance || !is_numeric($insurance) || ((float)$insurance)<0 ){
			throw new \Error('Insurance value must configured and be a positive number');
		}

		$insurance=(float)$insurance;
		$insurance/=100.0;
		$bid_items=[];
		$old_items=BidItem::query()->where('bid_id',$bid->id)->get()->keyBy('order_item_id');


		foreach ($data['equipments'] as $key=>$equipment){

			$details = $old_items[$key]->details;
			if(isset($equipment['attachments'])){
				$details['attachments']=$equipment['attachments'];
			}elseif(!isset($details['attachments'])){
				$details['attachments']=[];
			}


			$bid_items[$key]=[
				'price'=>$equipment['price'],
				'dropoff_fee'=>isset($equipment['pick'])?$equipment['pick']:0,
				'pickup_fee'=>isset($equipment['delivery'])?$equipment['delivery']:0,
				'insurance'=>(isset($equipment['insurance']) && $equipment['insurance'])?($insurance*$equipment['price']):0, //TODO: change to supplier settings
				'deliv_date'=>isset($equipment['from'])?$equipment['from']:null,
				'return_date'=>isset($equipment['to'])?$equipment['to']:null,
				'notes'=>isset($equipment['notes'])?$equipment['notes']:null,
				'status'=>BidItem::STATUS_DEFAULT,
				'details'=>$details
			];
		}


		DB::beginTransaction();
		foreach ($bid_items as $item_id=>$bid_item){
			$bid->items()->updateExistingPivot($item_id,$bid_item);
		}


		$amount=0;
		foreach ($bid->items as $item){
			$amount+=$item->pivot->price;
			$amount+=$item->pivot->dropoff_fee;
			$amount+=$item->pivot->pickup_fee;
			$amount+=$item->pivot->insurance;
		}
		$data['amount']=$amount;

		$bid=parent::updateBy($data,$bid->id);
		DB::commit();
		return $bid;

	}

	public function delete( $value = null, $field = 'id' ) {
		$bid = $this->findOneBy($value,$field);
		if(!$bid || !$bid->is_cancelable){throw new \Error('invalid access');}
		DB::beginTransaction();
		$bid->status=Bid::STATUS_CANCELED;
		$bid->save();
		DB::table('bid_order_item')->where('bid_id',$value)->update(['status'=>BidItem::STATUS_DEFAULT]);
		DB::commit();
	}

	public function approve($value=null,$field='id'){
		$bid = $this->findOneBy($value,$field);
		if(!$bid || !$bid->is_approvable){throw new \Error('invalid access');}
		$bid->status=Bid::STATUS_ACTIVE;
		$bid->save();
		return $bid;
	}

	public function close( Bid $bid ) {
		if(!$bid){
			throw new \Exception('invalid bid');
		}

		if($bid->status != Order::STATUS_VALID){
			throw new \Exception('Invalid status');
		}


		Mail::to($bid->order->site->contractor->users()->get()->pluck('email')->toArray())->send(new confirmedBid($bid));

		$bid->status=Bid::STATUS_CLOSED;
		$bid->save();

	}


}