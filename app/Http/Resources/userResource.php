<?php

namespace App\Http\Resources;

use App\Http\Resources\Geo\cityResource;
use App\Http\Resources\Geo\CountryResource;
use App\Http\Resources\Geo\metroResource;
use App\Http\Resources\Geo\StateResource;
use Illuminate\Http\Resources\Json\Resource;

class userResource extends Resource
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
	        'first_name'=>$this->first_name,
	        'last_name'=>$this->last_name,
	        'email'=>$this->email,
	        'phone'=>$this->phone,
	        'city'=>cityResource::make($this->city),
	        'metro'=>metroResource::make($this->metro),
	        'state'=>StateResource::make($this->state),
	        'country'=>CountryResource::make($this->country),
	        'parent'=>userResource::make($this->whenLoaded('creator_user')),
	        'postal_code'=>isset($this->settings['postal_code'])?$this->settings['postal_code']:'',
	        'secondary_phone'=>isset($this->settings['secondary_phone'])?$this->settings['secondary_phone']:'',
	        'address'=>isset($this->settings['address'])?$this->settings['address']:'',
	        'company_position'=>isset($this->settings['company_position'])?$this->settings['company_position']:'',
	        'company_name'=>isset($this->settings['company_name'])?$this->settings['company_name']:'',

        ];
    }
}
