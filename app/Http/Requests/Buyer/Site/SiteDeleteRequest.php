<?php

namespace App\Http\Requests\Buyer\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SiteDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return Auth::check()
	           && (
	           	(Auth::user()->rols->count()>0 && Auth::user()->rols->first()->id == $this->route('site')->id)
	            || (Auth::user()->hasAnyRol(['admin','superadmin','staff']))
	           );
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
