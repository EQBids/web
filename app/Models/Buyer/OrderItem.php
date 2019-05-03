<?php

namespace App\Models\Buyer;

use App\Models\Product\Equipment;
use App\Models\Supplier\Bid;
use App\Models\Supplier\BidItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Model
{
	const STATUS_ACTIVE=1;
	const STATUS_REJECTED=2;
	const STATUS_ACCEPTED=3;


    protected $fillable=['qty','order_id','status','equipment_id','deliv_date','return_date','notes'];
	public $timestamps=false;



    protected $table='order_items';

    protected $casts=[
    	'deliv_date'=>'date',
	    'return_date'=>'date'
    ];

    public function order(){
    	return $this->belongsTo(Order::class);
    }

    public function equipment(){
    	return $this->belongsTo(Equipment::class,'equipment_id')->withTrashed();
    }

    public function bids(){
    	return $this->
	    belongsToMany(Bid::class,'bid_order_item','order_item_id','bid_id')
		    ->using(BidItem::class)
		    ->with('supplier')
		    ->withPivot('price','dropoff_fee','pickup_fee','insurance','deliv_date','return_date','status','notes')
		    ->where('bids.status',Bid::STATUS_ACTIVE);
    }

    public function getAcceptedBidAttribute(){
    	$bid= $this->bids()->wherePivot('status',BidItem::STATUS_ACCEPTED)->first();
    	return $bid;
    }


}
