<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Http\Resources\Json\Resource;

class orderSupplierResource extends Resource
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
        	'status'=>$this->pivot->getStatusName(),
	        'id'=>$this->id,
	        'name'=>$this->name
        ];
    }
}
