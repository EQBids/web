<?php

namespace App\Http\Requests\Supplier\Orders;

use App\Models\OrderSupplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class rejectOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$user = Auth::user();
    	$order = $this->route('order');
	    $suppliers = $user->suppliers()->pluck('id');
	    if($order && $suppliers->count()){
	    	return OrderSupplier::query()->where('order_id',$order->id)->whereIn('supplier_id',$suppliers)->count()==1;
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
