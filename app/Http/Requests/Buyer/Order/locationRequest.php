<?php

namespace App\Http\Requests\Buyer\Order;

use App\Http\Requests\Buyer\Site\SiteCDRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class locationRequest extends SiteCDRequest
{


    /**
     *
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->is_contractor;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	if($this->has('new')){

		    $rules = parent::rules();
	    }else{
		    $rules['site']='required_without:new|nullable|integer|exists:sites,id';
	    }

    	return $rules;
    }

    public function messages() {
	    return [
	    	'site.required_without'=>'You must choose job site or create a new one',
		    'required_with'=>'The :attribute field is required when you are creating a new site'
	    ];
    }
}
