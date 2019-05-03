<?php

namespace App\Http\Resources\Buyer;

use App\Http\Resources\Supplier\bidItemResource;
use App\Http\Resources\Supplier\bidResource;
use App\Models\Supplier\BidItem;
use function foo\func;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class orderItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

    	$details = $this->equipment->details;
	    if(isset($details['image'])) {
		    $image = asset( implode( '/', array_map( function ( $v ) {
			    return rawurlencode( $v );
		    }, explode( '/', $details['image'] ) ) ) );
	    }else{
		    $image='';
	    }

	    return [
	    	'oid'=>$this->id,
		    'id'=>$this->equipment->id,
		    'name'=>$this->equipment->name,
		    'image'=>$image,
		    'from'=>$this->deliv_date?$this->deliv_date->format('Y-m-d'):null,
		    'to'=>$this->return_date?$this->return_date->format('Y-m-d'):null,
		    'qty'=>$this->qty,
		    'order_notes'=>($this->notes)?$this->notes:'',
		    'price'=>$this->whenPivotLoaded('bid_order_item',function(){
		    	return $this->pivot->price;
		    }),
		    'status'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->getStatusName();
		    }),
		    'pickup_fee'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->dropoff_fee;
		    }),
		    'delivery_fee'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->pickup_fee;
		    }),
		    'insurance'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->insurance;
		    }),
		    'deliv_date'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->deliv_date?$this->pivot->deliv_date->format('Y-m-d'):null;
		    }),
		    'return_date'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->return_date?$this->pivot->return_date->format('Y-m-d'):null;
		    }),
		    'notes'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->notes;
		    }),
		    'editable'=>$this->whenPivotLoaded('bid_order_item',function(){
			    return $this->pivot->status!=BidItem::STATUS_ACCEPTED;
		    }),
		    'bids'=>bidResource::collection($this->whenLoaded('bids')),
		    'allow_attachments'=>isset($this->equipment->details['allow_attachments'])?boolval($this->equipment->details['allow_attachments']):false,
		    'attachments'=>$this->whenPivotLoaded('bid_order_item',function(){
		    	return $this->pivot->attachments;
		    }),
		    'accepted_bid'=>$this->accepted_bid?bidResource::make($this->accepted_bid):null,
	    ];
    }
}
