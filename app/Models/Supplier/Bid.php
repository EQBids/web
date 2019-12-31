<?php

namespace App\Models\Supplier;

use App\Models\Buyer\Order;
use App\Models\Buyer\OrderItem;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bid extends Model
{

	const STATUS_ACTIVE=1;
	const STATUS_CANCELED=2;
	const STATUS_CLOSED=3;


    protected $fillable=['amount','details','supplier_id','order_id','user_id','status','price_w_fee'];
    protected $casts=[
    	'details'=>'json'
    ];

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }

    public function order(){
    	return $this->belongsTo(Order::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function items(){
    	return $this->belongsToMany(OrderItem::class,'bid_order_item','bid_id','order_item_id')
	                ->using(BidItem::class)->withPivot('price',
		    'dropoff_fee',
		    'pickup_fee',
		    'insurance',
		    'deliv_date',
		    'return_date',
		    'notes',
		    'status','details')->with('equipment');
    }

	public function getStatusName(){
		switch ($this->status){
			case Bid::STATUS_ACTIVE: return 'ACTIVE';
			case Bid::STATUS_CANCELED: return 'CANCELLED';
			case Bid::STATUS_CLOSED: return 'CLOSED';
			default: return 'UNKNOWN';
		}
	}

	public function getIsEditableAttribute(){
    	$user=Auth::user();
    	return ($this->status==Bid::STATUS_ACTIVE
	       && $user->is_supplier
	       && $user->hasAnyRol(['supplier-superadmin','supplier-admin'])
	       && $user->suppliers->pluck('id')->contains($this->supplier_id)
	       && ($this->order->status==Order::STATUS_VALID
	           || $this->order->status==Order::STATUS_HAS_BIDS));

	}

	public function getIsCancelableAttribute(){
		$user=Auth::user();

		return ($this->status==Bid::STATUS_ACTIVE
		        && $user->is_supplier
		        && $user->hasAnyRol(['supplier-superadmin','supplier-admin'])
		        && $user->suppliers->pluck('id')->contains($this->supplier_id)
		        && ($this->order->status==Order::STATUS_VALID
		            || $this->order->status==Order::STATUS_HAS_BIDS));

	}


	public function getIsApprovableAttribute(){
		$user=Auth::user();
		return ($this->status==Bid::STATUS_CANCELED
		        && $user->is_supplier
		        && $user->hasAnyRol(['supplier-superadmin','supplier-admin'])
		        && $user->suppliers->pluck('id')->contains($this->supplier_id)
		        && ($this->order->status==Order::STATUS_VALID
		            || $this->order->status==Order::STATUS_HAS_BIDS));

	}

	public function getIsAcceptedAttribute(){
    	return $this->items()->wherePivot('status',BidItem::STATUS_ACCEPTED)->count()>0;
	}

}
