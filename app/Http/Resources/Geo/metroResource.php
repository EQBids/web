<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/13/18
 * Time: 7:45 AM
 */

namespace App\Http\Resources\Geo;


use Illuminate\Http\Resources\Json\Resource;

class metroResource extends Resource {


	public function toArray($request)
	{
		return [
			'id'=>$this->id,
			'name'=>strtoupper($this->name),
			'status'=>strtoupper($this->status),
			'lat'=>$this->lat,
			'lon'=>$this->lon,
			'state'=>StateResource::make($this->state),
			'country'=>CountryResource::make($this->country)
		];
	}
}