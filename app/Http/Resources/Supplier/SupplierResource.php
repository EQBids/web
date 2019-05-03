<?php

namespace App\Http\Resources\Supplier;

use App\Http\Resources\Geo\cityResource;
use App\Http\Resources\Geo\CountryResource;
use App\Http\Resources\Geo\StateResource;
use Illuminate\Http\Resources\Json\Resource;

class SupplierResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $fields = [
        	'id'=>$this->id,
        	'name'=>$this->name,
	        'address'=>$this->address,
	        'country'=>$this->country?CountryResource::make($this->country):null,
	        'state'=>$this->state?StateResource::make($this->state):null,
	        'city'=>$this->city?cityResource::make($this->city):null,
	        ];

        if(isset($this->distance)){
        	$fields['distance']=$this->distance;
        }

        return $fields;
    }
}
