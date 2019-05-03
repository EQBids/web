<?php

namespace App\Http\Requests\Buyer\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class updateOrderBidsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$order = $this->route('order');
	    $user = Auth::user();
	    return $user->is_contractor && $order->can_assign_bids;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	return [
            'bids'=>'nullable|array',
	        'bids.*.id'=>['integer',Rule::exists('order_items','id')->where('order_id',$this->route('order')->id)],
	        'bids.*.bid'=>['nullable','integer',Rule::exists('bids','id')->where('order_id',$this->route('order')->id)],
        ];
    }
}
