<?php

namespace App\Http\Resources\Geo;

use Illuminate\Http\Resources\Json\Resource;

class CountryResource extends Resource
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
	        'name'=>strtoupper($this->name),
	        'iso'=>strtoupper($this->iso_code),
	        'status'=>strtoupper($this->status)
        ];
    }
}
