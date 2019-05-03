<?php

namespace App\Http\Requests\Admin\Industries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class industryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|max:250',
            'parent'=>'nullable|integer|exists:industries,id'
        ];
    }
}
