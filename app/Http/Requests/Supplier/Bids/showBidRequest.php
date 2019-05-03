<?php

namespace App\Http\Requests\Supplier\Bids;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class showBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$bid = $this->route('bid');
	    return Auth::user()->is_supplier && $bid && Auth::user()->suppliers->pluck('id')->contains($bid->supplier_id);
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
