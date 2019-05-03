<?php

namespace App\Http\Requests\Buyer\Office;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateOfficeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasAnyRol([
                'contractor-superadmin',
                'contractor-manager',
                'contractor-worker',
                'contractor-admin',
            ]);
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
            'address'           =>          'required|string|max:200',
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
            'postal_code'       =>          'nullable|string|max:8',
            'details'           =>          'nullable|string|max:500',
	        'image' => 'filled|file|image|max:2048'
        ];
    }
}
