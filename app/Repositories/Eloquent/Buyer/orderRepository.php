<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 5:32 PM
 */

namespace App\Repositories\Eloquent\Buyer;


use App\Mail\orders\closureWinner;
use App\Mail\orders\lossingBid;
use App\Mail\orders\newOrder;
use App\Mail\orders\updatedOrder;
use App\Models\Billing;
use App\Models\Buyer\Cart;
use App\Models\Buyer\CartEquipment;
use App\Models\Buyer\Order;
use App\Models\EmailSent;
use App\Models\Product\Equipment;
use App\Models\Supplier\BidItem;
use App\Models\Supplier\Supplier;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\Supplier\supplierRepository;
use App\Repositories\Interfaces\Buyer\orderRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class orderRepository extends BaseRepository implements orderRepositoryInterface {

	protected $supplier_repository,$site_repository;
	public function __construct(Order $model,supplierRepository $supplier_repository,siteRepository $site_repository ) {
		parent::__construct( $model );
		$this->supplier_repository=$supplier_repository;
		$this->site_repository=$site_repository;
	}

	public function create( array $data ) {
		if (!isset($data['status'])){
			$data['status']=0;
		}

		if (!isset($data['user_id'])){
			$data['user_id']=Auth::user()->id;
		}

		return parent::create( $data ); // TODO: Change the autogenerated stub
	}

	public function createFromCart( Cart $cart ) {
		$items_data = [];
		foreach($cart->items as $item){
			array_push($items_data,[	'qty'=>$item->pivot->qty,
				'deliv_date'=>$item->pivot->from,
				'return_date'=>$item->pivot->to,
				'notes'=>($item->pivot->extras && isset($item->pivot->extras['notes']))?$item->pivot->extras['notes']:' ',
				'equipment_id'=>$item->id,
			]);
		}
		DB::beginTransaction();
		try {
			$orderData = [
				'site_id'      => $cart->details['site_id'],
				'status'       => Order::STATUS_VALID,
				'notes'        => ' ',
				'extra_fields' => [],
				'user_id'=>$cart->user->id,
			];

			$order = $this->create( $orderData );
			$order->items()->createMany( $items_data );
			$order->suppliers()->sync($cart->details['suppliers']);
			DB::commit();

			$this->sendNewOrderEmail($order,$cart);
			$cart->delete();
			return $order;
		}catch (Exception $exception){
			DB::rollback();
			return null;
		}
	}

	private function sendNewOrderEmail($order,$cart){
		$users = $this->supplier_repository->supplierUsers($cart->details['suppliers'],['email']);
		$emails = $users->pluck('email')->unique();

		try {
			Mail::bcc( $emails )->send( new newOrder() );
		}catch(\Exception $error){
			//
		}
		$sendemails_data=[];
		foreach ($cart->details['suppliers'] as $supplier){
			array_push($sendemails_data,[
				'order_id'=>$order->id,
				'supplier_id'=>$supplier,
				'details'=>'[]',
				'created_at'=>now()
			]);
		}

		EmailSent::insert($sendemails_data);
	}

	private function sendUpdatedOrderEmail($order,$suppliers_ids){
		//new suppliers
		$users = $this->supplier_repository->supplierUsers($suppliers_ids['attached'],['email']);
		$emails = $users->pluck('email')->unique();

		Mail::bcc($emails)->send(new newOrder());
		$sendemails_data=[];
		foreach ($suppliers_ids['attached'] as $supplier){
			array_push($sendemails_data,[
				'order_id'=>$order->id,
				'supplier_id'=>$supplier,
				'details'=>'[]',
				'created_at'=>now()
			]);
		}

		//updated suppliers
		$suppliers_to_notify=$order->suppliers->pluck('id')->toArray();
		$suppliers_to_notify=array_diff($suppliers_to_notify,$suppliers_ids['attached']);
		$users = $this->supplier_repository->supplierUsers($suppliers_to_notify,['email']);
		$emails = $users->pluck('email')->unique();

		Mail::bcc($emails)->send(new updatedOrder($order));

	}

	public function accesibleOrders( User $user ) {
		if (!$user){
			$this->query->whereIn('site_id',[0]); //nothing
		}
		if($user->is_contractor && $user->contractor != null){
			if ($user->hasAnyRol(['contractor-worker','contractor-manager'])){
				$sites = $user->sites()->pluck('id');
				$this->query->whereIn('site_id',$sites);
			}else{
				$sites = $user->contractor->sites()->pluck('id');
				$this->query->whereIn('site_id',$sites);
			}
		}elseif($user->is_supplier){
			$suppliers_id = $user->suppliers()->pluck('id');
			$this->query->whereExists(function($exists) use ($suppliers_id){
				$exists->select(DB::raw(1))
				      ->from('order_supplier')
				      ->whereRaw('orders.id = order_supplier.order_id')->whereIn('supplier_id',$suppliers_id);
			});
		}
		else{
			$this->query->whereIn('site_id',[0]); //nothing
		}
		$this->query->orderBy('created_at','desc');

		return $this;
	}


	public function ordersFromSites($site_ids){
		$this->query->whereIn('site_id',$site_ids);
		return $this->findAll();
	}

	public function beginEditProcess( Order $order ) {
		if ($order->is_editable) {
			$old_status                               = $order->status;
			$extra_fields = $order->extra_fields;
			$extra_fields['editing']= [];
			$extra_fields['editing']['status'] = $old_status;
			$extra_fields['editing']['items']=$order->items->map(function ($item,$key){
				return [
					'id'=>$item->equipment_id,
					'qty'=>$item->qty,
					'from'=>$item->deliv_date->format('Y-m-d'),
					'to'=>$item->return_date->format('Y-m-d'),
					'notes'=>$item->notes
				];
			})->toArray() ;
			$order->extra_fields = $extra_fields;
			$order->status                            = Order::STATUS_EDITING;

			$order->save();
			return true;
		}
		return false;
	}

	public function cancelEditProcess(Order $order){
		if ($order->status != Order::STATUS_EDITING){
			return false;
		}
		$extra_fields = $order->extra_fields;
		$order->status = Order::STATUS_ON_APPROVAL; //fallback$
		if (isset($extra_fields['editing'])){
			$order->status = $extra_fields['editing']['status'];
		}
		unset($extra_fields['editing']);
		$order->extra_fields=$extra_fields;
		$order->save();
		return true;
	}

	public function getEditingSite( Order $order ) {
		$extra_fields = $order->extra_fields;
		if(isset($extra_fields['editing']['site'])){
			return $this->site_repository->findOneBy($extra_fields['editing']['site']);
		}
		return $order->site;
	}

	public function setEditingSite( Order $order, $site ) {
		$extra_fields = $order->extra_fields;
		$extra_fields['editing']['site']=is_object($site)?$site->id:$site;
		if($extra_fields['editing']['site'] != $order->site->id){
			$extra_fields['editing']['suppliers']=[];
		}
		$order->extra_fields = $extra_fields;
		$order->save();
		return true;
	}

	public function getEditingSuppliers( Order $order ) {
		$extra_fields = $order->extra_fields;
		if (isset($extra_fields['editing']) && isset($extra_fields['editing']['suppliers'])) {
			return $this->supplier_repository->findAllWhereIn($extra_fields['editing']['suppliers'],'id');
		}
		return $order->suppliers;
	}

	public function setEditingSuppliers( Order $order, $suppliers ) {
		$extra_fields = $order->extra_fields;
		$extra_fields['editing']['suppliers']=$suppliers;
		$order->extra_fields = $extra_fields;
		$order->save();
		return true;
	}

	public function getEditingItems( Order $order ) {
		$extra_fields = $order->extra_fields;
		return collect($extra_fields['editing']['items'])->map(function ($item,$key){
			$eq = Equipment::find($item['id']);
			$item['name']=$eq->name;
			$item['allow_attachments']=isset($eq->details['allow_attachments'])?boolval($eq->details['allow_attachments']):false;
			$item['image']=$eq->details['image'];
			return $item;
		});
	}

	public function setEditingItems( Order $order, $items ) {
		$extra_fields = $order->extra_fields;
		$extra_fields['editing']['items']=$items;
		$order->extra_fields = $extra_fields;
		$order->save();
		return true;
	}

	public function finishEditing( Order $order ) {
		$extra_fields = $order->extra_fields;
		if ($order->is_editing) {
			$editing_site = $this->getEditingSite($order);
			$editing_suppliers = $this->getEditingSuppliers($order);
			$editing_items = $this->getEditingItems($order);
			if(!$editing_suppliers->count()){
				throw new \Exception('No suppliers selected');
			}
			$items_data = [];
			foreach($editing_items as $item){
				array_push($items_data,[	'qty'=>$item['qty'],
				                            'deliv_date'=>$item['from'],
				                            'return_date'=>$item['to'],
				                            'notes'=>isset($item['notes'])?$item['notes']:' ',
				                            'equipment_id'=>$item['id'],
				]);
			}
			DB::beginTransaction();
			try {
				$orderData = [
					'site_id'      => $editing_site->id,
					'status'       => $extra_fields['editing']['status'],
					'notes'        => ' ',
				];
				unset($extra_fields['editing']);
				$orderData['extra_fields']=$extra_fields;

				$order = $this->updateBy( $orderData,$order->id);
				$order->items()->delete();
				$order->items()->createMany( $items_data );
				$old_suppliers=$order->suppliers;
				$sync_result=$order->suppliers()->sync($editing_suppliers);
				DB::commit();
				$this->sendUpdatedOrderEmail($order,$sync_result);
				return $order;
			}catch (Exception $exception){
				DB::rollback();
				return null;
			}

		}
		throw new \Exception("Order is not being edited");
	}

	public function updateBids( Order $order, $bids ) {
		if(!$order->can_assign_bids){
			throw new \Error('Invalid access');
		}

		$items = $order->items->keyBy('id');
		DB::beginTransaction();
		try{
			foreach ($bids as $bid){
				if(isset($bid['id']) && isset($bid['bid'])  && $bid['bid'] && isset($items[$bid['id']])){
					$item = $items[$bid['id']];
					$itemBid=$item->bids()->where('bids.id',$bid['bid'])->first();
					if($itemBid){
						BidItem::query()->where('order_item_id',$bid['id'])->update(['status'=>BidItem::STATUS_DEFAULT]);
						$item->bids()->updateExistingPivot($bid['bid'],['status'=>BidItem::STATUS_ACCEPTED]);
					}
				}
			}
			DB::commit();
			return $order;
		}catch(\Exception $exception){
			DB::rollback();
			throw  $exception;
		}
	}

	public function delete( $value = null, $field = 'id' ) {

		$order = $this->findOneBy($value,$field);
		if($order){
			$order->cancel();
			$order->save();
			return true;
		}
		return null;


	}

	public function close( Order $order ) {
		if(!$order){
			throw new \Exception('invalid order');
		}
		if($order->status != Order::STATUS_VALID){
			throw new \Exception('Invalid status');
		}

		$suppliers = $order->suppliers()->get(['id'])->pluck('id');
		$winning_suppliers = $order->bids()->whereHas('items',function ($query){
				$query->where('bid_order_item.status',BidItem::STATUS_ACCEPTED);
		})->get()->map(function ($bid){
			return $bid->supplier_id;
		})->unique();
		$no_winners = $suppliers->diff($winning_suppliers);
		$this->sendCloseOrderEmails($order,$winning_suppliers,$no_winners);

		$this->updateBy([
			'status'=>Order::STATUS_CLOSED
		],$order->id);


	}

	private function sendCloseOrderEmails($order,$winning_suppliers,$no_winning_suppliers){
		//winning suppliers
		$users = $this->supplier_repository->supplierUsers($winning_suppliers,['email']);
		$emails = $users->pluck('email')->unique();

		Mail::bcc($emails)->send(new closureWinner($order));

		//losing suppliers
		$users = $this->supplier_repository->supplierUsers($no_winning_suppliers,['email']);
		$emails = $users->pluck('email')->unique();

		Mail::bcc($emails)->send(new lossingBid($order));

	}


}