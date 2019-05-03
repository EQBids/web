<?php

namespace App\Http\Resources\Buyer;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class editingOrderItemsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
	    if(isset($this['image'])) {
		    $image = asset( implode( '/', array_map( function ( $v ) {
			    return rawurlencode( $v );
		    }, explode( '/', $this['image'] ) ) ) );
	    }else{
		    $image='';
	    }
	    return [
		    'id'=>$this['id'],
		    'name'=>$this['name'],
		    'image'=>$image,
		    'from'=>$this['from']?$this['from']:null,
		    'to'=>$this['to']?$this['to']:null,
		    'qty'=>$this['qty'],
		    'order_notes'=>$this['notes']?$this['notes']:'',
		    'allow_attachments'=>isset($this['allow_attachments'])?$this['allow_attachments']:false,
	    ];
    }
}
