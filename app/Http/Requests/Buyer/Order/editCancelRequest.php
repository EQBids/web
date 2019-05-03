<?php

namespace App\Http\Requests\Buyer\Order;

use Illuminate\Foundation\Http\FormRequest;

class editCancelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    $order = $this->route('order');
	    if($order) return $order->is_editing;
	    return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
