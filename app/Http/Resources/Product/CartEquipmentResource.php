<?php

namespace App\Http\Resources\Product;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class CartEquipmentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       $details = $this->details;
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
	        'from'=>$this->pivot->from?$this->pivot->from->format('Y-m-d'):null,
	        'to'=>$this->pivot->to?$this->pivot->to->format('Y-m-d'):null,
	        'qty'=>$this->pivot->qty,
	        'order_notes'=>$this->whenPivotLoaded('cart_equipment',function (){
				return ($this->pivot->extras && isset($this->pivot->extras['notes']))?$this->pivot->extras['notes']:'';
	        }),
	        'allow_attachments'=>isset($this->details['allow_attachments'])?boolval($this->details['allow_attachments']):false,
        ];
    }
}
