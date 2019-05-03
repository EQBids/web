<?php

namespace App\Http\Requests\Buyer\Site;

use App\Rules\allowedCrudRole;
use App\Rules\allowedUseContractor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SiteCDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

	    return Auth::check() && (sizeof(Auth::user()->contractors)>0 || Auth::user()->hasAnyRol(['admin','superadmin','staff']));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules= [
        	'nickname'=>'nullable|string|max:100',
	        'name'=>'required|string|max:100',
	        'address'=>'required|string|max:250',
            'city'=>[
	            'required',
	            'integer',
	            Rule::exists('cities','id')->where(function($query){
		            $query->where('country_id',$this->input('country'));
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
            'zip'=>'nullable|string|max:10',
            'phone'=>'required|string|max:15',
			'contact'=>'nullable|string|max:100',
	        'special_instructions'=>'nullable|string|max:500'
        ];

        if(!Auth::user()->hasAnyRol(['admin','superadmin','staff'])){
	        $rules['contractor']=[
		        'nullable',
		        'integer',
		        new allowedUseContractor(Auth::user()->id),
	        ];
        }else{
	        $rules['contractor']='required|integer|exists:contractors,id';
        }

        return $rules;

    }
}
