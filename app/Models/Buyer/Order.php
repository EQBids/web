<?php

namespace App\Models\Buyer;

use App\Models\Message;
use App\Models\OrderSupplier;
use App\Models\Product\Equipment;
use App\Models\Supplier\Bid;
use App\Models\Supplier\BidItem;
use App\Models\Supplier\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{

	const STATUS_ON_APPROVAL=0;
	const STATUS_VALID=1;
	const STATUS_CANCELLED=2;
	const STATUS_HAS_BIDS=3;
	const STATUS_BIDS_ACEPTED=4;
	const STATUS_FULFULLED=5;
	const STATUS_EDITING=6;
	const STATUS_CLOSED=7;



	protected $fillable=['status','site_id','user_id','billing_id','deliv_date','return_date','notes','extra_fields'];

	protected $casts=[
		'extra_fields'=>'json',
		'deliv_date'=>'date',
		'return_date'=>'date'
	];

	protected $attributes=[
		'notes'=>' ',
		'extra_fields'=>'[]'
	];



	public function bids(){
		return $this->hasMany(Bid::class);
	}

	public function equipment(){
		return $this->belongsTo(Equipment::class);
	}

	public function creator(){
		return $this->belongsTo(User::class,'user_id');
	}

	public function site(){
		return $this->belongsTo(Site::class);
	}

	public function contractor(){
		return $this->belongsTo(Contractor::class);
	}

	public function messages(){
		return $this->hasMany(Message::class);
	}

	public function items(){
		return $this->hasMany(OrderItem::class,'order_id')->with('equipment');
	}

	public function suppliers(){
		return $this->belongsToMany(Supplier::class)->withPivot(['status'])->using(OrderSupplier::class);
	}

	public function scopeInProcess($query){
		return $query->where('status',0);
	}


	public function getStatusName(){
		switch ($this->status){
			case Order::STATUS_ON_APPROVAL: return 'UPON APROVAL';
			case Order::STATUS_VALID: return 'ACTIVE';
			case Order::STATUS_CANCELLED: return 'CANCELLED';
			case Order::STATUS_HAS_BIDS: return 'HAS BIDS';
			case Order::STATUS_BIDS_ACEPTED: return 'ACCEPTED BIDS';
			case Order::STATUS_FULFULLED: return 'FULFILLED';
			case Order::STATUS_EDITING: return 'EDITING';
			case Order::STATUS_CLOSED: return 'CLOSED';
			default: return 'UNKNOWN';
		}
	}

	public function getIsEditableAttribute(){
		return $this->isEditableBy(Auth::user());
	}

	public function getIsCancelableAttribute(){
		return $this->isCancelableBy(Auth::user());
	}

	public function getIsApprovableAttribute(){
		return $this->isApprovableBy(Auth::user());
	}


	public function isEditableBy(User $user){
		if($this->status != Order::STATUS_ON_APPROVAL && $this->status != Order::STATUS_VALID){
			return false;
		}
		if ($user->is_admin){
			return true;
		}
		if($user->is_contractor){
			if($user->hasRole('contractor-worker') && $user->id==$this->user_id){
				return true;
			}
			if($user->hasRole('contractor-manager') && $user->sites()->where('id',$this->site_id)->count()){
				return true;
			}
			return $user->contractors()->first()->sites()->where('id',$this->site_id)->count()>0;
		}
		return false;
	}

	public function isCancelableBy(User $user){
		if ($this->status == Order::STATUS_CANCELLED || $this->status==Order::STATUS_CLOSED){
			return false;
		}
		if ($user->is_admin){
			return true;
		}
		if($user->is_contractor){
			if($user->hasRole('contractor-worker')){
				return false;
			}
			if($user->hasRole('contractor-manager') && $user->sites()->where('id',$this->site_id)->count()){
				return true;
			}
			return $user->contractors()->first()->sites()->where('id',$this->site_id)->count()>0;
		}
		return false;
	}

	public function isApprovableBy(User $user){
		if ($this->status !== Order::STATUS_ON_APPROVAL || $this->status==Order::STATUS_CLOSED){
			return false;
		}
		if ($user->is_admin){
			return true;
		}
		if($user->is_contractor){
			if($user->hasRole('contractor-worker')){
				return false;
			}
			if($user->hasRole('contractor-manager') && $user->sites()->where('id',$this->site_id)->count()){
				return true;
			}
			return $user->contractors()->first()->sites()->where('id',$this->site_id)->count()>0;
		}
		return false;
	}

	public function approve(){
		if($this->getIsApprovableAttribute()){
			$this->status=Order::STATUS_VALID;
		}
		return false;
	}

	public function cancel(){
		if($this->getIsCancelableAttribute()){
			$this->status=Order::STATUS_CANCELLED;
		}
		return false;
	}

	public function getIsEditingAttribute(){
		return $this->status == Order::STATUS_EDITING;
	}


	public function getHasBids(){
		return $this->bids()->count()>0;
	}

	public function getHasBidsAttribute(){
		return $this->getHasBids();
	}


	public function getCanAssignBidsAttribute(){
		$user = Auth::user();
		return $user->is_contractor && $this->getHasBids() && $this->status!=Order::STATUS_CLOSED;
	}

	public function getHasAcceptedBidsAttribute(){
		return $this->bids()->whereHas('items',function ($q){
				$q->where('bid_order_item.status',BidItem::STATUS_ACCEPTED);
			})->count()>0;
	}





}
