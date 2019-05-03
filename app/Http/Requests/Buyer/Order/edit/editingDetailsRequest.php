<?php

namespace App\Http\Requests\Buyer\Order\edit;

use App\Http\Requests\Buyer\Order\detailsRequest;
use Illuminate\Foundation\Http\FormRequest;

class editingDetailsRequest extends detailsRequest
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

	public function rules()
	{
		$rules=parent::rules();

		$rules['equipments.*.from']='required|date'; //TODO: this should be today instead, but we need to define a way to properly handle timezones

		return $rules;

	}

}
