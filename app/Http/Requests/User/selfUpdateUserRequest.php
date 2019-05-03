<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class selfUpdateUserRequest extends updateSubUserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
	    $rules['email']=['required','string','email','max:100',Rule::unique('users','email')->ignore(Auth::user()->id)];
	    unset($rules['role']);
        return $rules;
    }
}
