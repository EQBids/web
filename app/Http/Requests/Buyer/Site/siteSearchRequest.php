<?php

namespace App\Http\Requests\Buyer\Site;

use Illuminate\Foundation\Http\FormRequest;

class siteSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname'=>'nullable|string|max:100',
	        'name'=>'nullable|string|max:100'
        ];
    }
}
