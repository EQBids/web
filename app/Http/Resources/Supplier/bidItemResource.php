<?php

namespace App\Http\Resources\Supplier;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Supplier\BidItem;

class bidItemResource extends Resource
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
	        'price'=>$this->price,
	        'status'=>$this->getStatusName(),
	        'pickup_fee'=>$this->dropoff_fee,
	        'delivery_fee'=>$this->pickup_fee,
	        'insurance'=>$this->insurance,
	        'deliv_date'=>$this->deliv_date?$this->deliv_date->format('Y-m-d'):null,
	        'return_date'=>$this->return_date?$this->return_date->format('Y-m-d'):null,
	        'notes'=>$this->notes,
	        'editable'=>$this->status!=BidItem::STATUS_ACCEPTED,
	        
        ];
    }
}
