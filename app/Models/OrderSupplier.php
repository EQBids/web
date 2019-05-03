<?php

namespace App\Models;

use App\Models\Buyer\Order;
use App\Models\Supplier\Bid;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class OrderSupplier extends Pivot
{
	const STATUS_ACTIVE=1;
	const STATUS_DISABLED=2;
	const STATUS_REJECTED=3;

	public $timestamps=false;

    protected $fillable=['order_id','supplier_id','status'];

	public function getStatusName(){
		switch ($this->status){
			case OrderSupplier::STATUS_ACTIVE: return 'ACTIVE';
			case OrderSupplier::STATUS_DISABLED: return 'DISABLED';
			case OrderSupplier::STATUS_REJECTED: return 'REJECTED';
			default: return 'UNKNOWN';
		}
	}

	public function order(){
		return $this->belongsTo(Order::class);
	}

	public function supplier(){
		return $this->belongsTo(Supplier::class);
	}

	public function getIsCancelableAttribute(){
		if ($this->status==OrderSupplier::STATUS_ACTIVE){
			$user = Auth::user();
			if($user->is_supplier){
				return $user->hasAnyRol(['supplier-superadmin','supplier-admin','supplier-manager']);
			}
		}
		return false;
	}

	public function bid(){
		return $this->hasOne(Bid::class,'order_id','order_id')
		            ->where('supplier_id',$this->supplier_id);
	}


	public function getIsBidableAttribute(){
		$order_status=$this->order->status;
		if($order_status==Order::STATUS_ON_APPROVAL ||
		   $order_status==Order::STATUS_CANCELLED ||
		   $order_status==Order::STATUS_FULFULLED ||
		   $order_status==Order::STATUS_EDITING){
			return false;
		}
		if($this->status==OrderSupplier::STATUS_ACTIVE){
			return $this->bid==null;
		}
		return false;
	}

}
