<?php

namespace App\Http\Resources\Buyer;

use App\Http\Resources\userResource;
use Illuminate\Http\Resources\Json\Resource;

class orderResource extends Resource
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
	        'created_at'=>$this->created_at->format('Y/m/d'),
	        'creator'=>userResource::make($this->creator),
	        'destination'=>$this->site,
	        'status'=>$this->getStatusName(),
	        'edit'=>$this->is_editable,
            'cancel'=>$this->is_cancelable,
            'approve'=>$this->is_approvable,
			'bids'=>$this->can_assign_bids,
	        'has_bids'=>$this->has_bids,
            'notes'=>$this->notes,
	        'closable'=>$this->ha
        ];
    }
}
