<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\Resource;
use Mews\Purifier\Facades\Purifier;

class CategoryResource extends Resource
{

	/**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	$details = $this['details'];
	    if(isset($details['image'])) {
		    $image = asset( implode( '/', array_map( function ( $v ) {
			    return rawurlencode( $v );
		    }, explode( '/', $details['image'] ) ) ) );
	    }else{
		    $image='';
	    }
        return [
            'id'=>$this->id,
	        'name'=>$this->name,
	        'image'=>$image,
	        'description'=>isset($details['description'])?clean(html_entity_decode($details['description'])):'',
			'childs'=>CategoryResource::collection($this->whenLoaded('childs'))
        ];
    }
}
