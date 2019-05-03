<?php

namespace App\Http\Requests\Applicant;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class ApplicantExistsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$applicant = $this->route('applicant');
    	if(!is_object($applicant)){
    		$applicant=User::find($applicant);
	    }
	    if ($applicant){
    		return $applicant->status==User::STATUS_ON_APPROVAL || $applicant->status==User::STATUS_REJECTED;
	    }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
