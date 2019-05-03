<?php

namespace App\Http\Requests\Buyer\Order\edit;

use App\Http\Requests\Buyer\Order\siteOnlyRequest;
use Illuminate\Foundation\Http\FormRequest;

class siteRequest extends siteOnlyRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	if(parent::authorize()){
		    $order = $this->route('order');
		    if($order) return $order->is_editing;
	    }
	    return false;
    }

}
