<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use App\Rules\allowedCrudRole;
use App\Rules\belongOrOwnsSite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class userCDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->rols->count()>0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    $rules= [
	    	'role'=>['required','integer',new allowedCrudRole],
		    'first_name'=>'required|string|max:100',
		    'last_name'=>'required|string|max:100',
		    'email'=>'required|string|email|max:100|unique:users',
		    'company_name'=>'nullable|string|max:100',
		    'company_position'=>'nullable|string|max:100',
		    'address'=>'nullable|string|max:200',
		    'city_id'=>[
			    'required_with:address',
			    'integer',
			    Rule::exists('cities','id')->where(function($query){
				    $query->where('country_id',$this->input('country_id'));
				    if ($this->input('state_id')){
				    	$query->where('state_id',$this->input('state_id'));
				    }
			    }),
		    ],
		    'state_id'=>[
			    'nullable',
			    'integer',
			    Rule::exists('states','id')->where(function($query){
				    $query->where('country_id',$this->input('country_id'));
			    }),
		    ],
		    'country_id'=>'required|integer|exists:countries,id',
		    'postal_code'=>'nullable|string|max:8',
		    'phone'=>'nullable|string|max:15',
		    'phone_alt'=>'nullable|string|max:15',
		    'status'=>'integer|min:0',

	    ];
	    $rules['site_id']=['integer'];
	    if(Auth::user()->is_contractor){
		    array_push($rules['site_id'],new belongOrOwnsSite(Auth::user()->contractors->first()->id));
	    }
	    $rules['contractor_id']=['integer',Rule::exists('contractors','id')->where('user_id',Auth::user()->id)];
	    $desiredRol = Role::find($this->get('role'));
	    if($desiredRol && ($desiredRol->name=='contractor-worker' || $desiredRol->name=='contractor-manager') && Auth::user()->is_admin){
		    $rules['site_id'] = array_prepend($rules['site_id'],'required');
	    }

	    if($desiredRol && $desiredRol->name=='contractor-admin'  && Auth::user()->is_admin){
		    $rules['contractor_id']= array_prepend($rules['contractor_id'],'required');
	    }

	    return $rules;
    }
}
