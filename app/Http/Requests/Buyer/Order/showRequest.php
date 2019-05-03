<?php

namespace App\Http\Requests\Buyer\Order;

use App\Repositories\Eloquent\Buyer\orderRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class showRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $order = $this->route('order');
        if($order){
        	$order_repository = app()->make(orderRepository::class);
	        return $order_repository->accesibleOrders(Auth::user())->findOneBy($order->id);

        }
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
