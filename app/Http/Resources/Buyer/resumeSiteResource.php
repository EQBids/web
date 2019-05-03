<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Http\Resources\Json\Resource;

class resumeSiteResource extends Resource
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
        	'name'=>$this->nickname?$this->nickname:$this->name,
	        'city'=>$this->city?$this->city->name:null,
	        'state'=>$this->state?$this->state->iso_code:null
        ];
    }
}
