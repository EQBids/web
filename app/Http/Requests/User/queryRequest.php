<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class queryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //handled in requiredRolMiddleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=>'nullable|string|max:100',
            'last_name'=>'nullable|string|max:100',
	        'email'=>'nullable|string|max:50',
	        'rol'=>'nullable|integer|exists:rols,id'
        ];
    }
}
