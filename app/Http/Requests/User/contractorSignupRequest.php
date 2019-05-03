<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class contractorSignupRequest extends FormRequest
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
			'first_name'=>'required|string|max:100',
			'last_name'=>'required|string|max:100',
	        'email'=>'required|string|email|max:100|unique:users',
	        'company_name'=>'required|string|max:100',
	        'company_position'=>'nullable|string|max:100',
	        'address'=>'nullable|string|max:200',
	        'city'=>[
	        	    'required',
		            'integer',
		            Rule::exists('cities','id')->where(function($query){
						$query->where('state_id',$this->input('state'))
							  ->where('country_id',$this->input('country'));
		            }),
		        ],
			'metro'=>[
				'nullable',
				'integer',
				Rule::exists('metros','id')->where(function($query){
					$query->where('state_id',$this->input('state'))
					      ->where('country_id',$this->input('country'));
				}),
			],
			'state'=>[
					'required',
					'integer',
					Rule::exists('states','id')->where(function($query){
						$query->where('country_id',$this->input('country'));
					}),
				],
			'country'=>'required|integer|exists:countries,id',
			'postal_code'=>'nullable|string|max:8',
	        'phone'=>'required|string|max:15',
	        'phone_alt'=>'nullable|string|max:15',

        ];
    }
}
