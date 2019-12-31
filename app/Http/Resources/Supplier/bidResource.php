<?php

namespace App\Http\Resources\Supplier;

use App\Http\Resources\Buyer\orderItemResource;
use App\Http\Resources\Buyer\orderResource;
use Illuminate\Http\Resources\Json\Resource;

class bidResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	return [
        	'id'=>$this->id,
            'supplier'=>SupplierResource::make($this->supplier),
            'amount'=>$this->amount,
            'price_w_fee'=>$this->price_w_fee,
	        'status'=>$this->resource->getStatusName(),
	        'total'=>$this->whenPivotLoaded('bid_order_item',function(){
		        return $this->pivot->total;
	        }),
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
	            return $this->pivot->notes!=null?$this->pivot->notes:'';
            }),
	        'items'=>$this->whenLoaded('items',function (){
	        	return orderItemResource::collection($this->items);
	        }),
	        'order'=>$this->whenLoaded('order',function (){
	        	return orderResource::make($this->order);
	        })
        ];
    }
}
