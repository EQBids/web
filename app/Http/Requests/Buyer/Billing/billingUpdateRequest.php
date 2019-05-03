<?php

namespace App\Http\Requests\Buyer\Billing;

use App\Rules\allowedUseContractor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class billingUpdateRequest extends billingCURequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return Auth::check()
	           && Auth::user()->hasAnyRol(['superadmin','admin','staff','contractor-superadmin','contractor-admin','contractor-manager','contractor-worker'])
		        && (new allowedUseContractor(Auth::user()->id))->passes('',$this->route('contractor'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['contractor']);
        return $rules;
    }
}
