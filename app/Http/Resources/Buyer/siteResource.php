<?php

namespace App\Http\Resources\Buyer;

use App\Http\Resources\Geo\cityResource;
use App\Http\Resources\Geo\CountryResource;
use App\Http\Resources\Geo\metroResource;
use App\Http\Resources\Geo\StateResource;
use Illuminate\Http\Resources\Json\Resource;

class siteResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array(
        	'id'=>$this->id,
        	'nickname'=>$this->nickname,
	        'name'=>$this->name,
	        'address'=>$this->address,
	        'city'=>cityResource::make($this->city),
	        'metro'=>metroResource::make($this->city),
	        'state'=>StateResource::make($this->city),
	        'country'=>CountryResource::make($this->city),
	        'zip'=>$this->zip,
	        'contact'=>$this->contact,
	        'details'=>$this->details,
        );
    }
}
