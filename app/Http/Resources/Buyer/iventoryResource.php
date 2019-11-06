<?php

namespace App\Http\Resources\Buyer;

use App\Http\Resources\Supplier\bidItemResource;
use App\Http\Resources\Supplier\bidResource;
use App\Models\Supplier\BidItem;
use function foo\func;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class iventoryResource extends Resource
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
		    'id'=>$this->equipment->id
		    
	    ];
    }
}
