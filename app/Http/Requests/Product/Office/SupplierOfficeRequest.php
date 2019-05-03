<?php

namespace App\Http\Requests\Product\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierOfficeRequest extends FormRequest
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
            'location'          =>          'required|max:150',
            'address'           =>          'required',
            'country'           =>          'required|integer|exists:countries,id',
            'state'             =>          [
                'required',
                'integer',
                Rule::exists('states','id')->where(function($query){
                    $query->where('country_id',$this->input('country'));
                }),
            ],
            'city'              =>          [
                'required',
                'integer',
                Rule::exists('cities','id')->where(function($query){
                    $query->where('state_id',$this->input('state'))
                        ->where('country_id',$this->input('country'));
                }),
            ],
            'details'           =>          'nullable',
            'image'             =>          'nullable|file|mimes:jpeg,bmp,png|max:2048'
        ];
    }
}
