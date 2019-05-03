<?php

namespace App\Http\Requests\Geo\Country;

use Illuminate\Foundation\Http\FormRequest;

class CountryIndexRequest extends FormRequest
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
            'name'=>'nullable|string|max:100'
        ];
    }
}
