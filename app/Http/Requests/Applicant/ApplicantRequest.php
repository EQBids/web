<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApplicantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        dd($this->route('applicant'));
        return [
            'first_name'=>'required|string|max:100',
            'last_name'=>'required|string|max:100',
            'email'=>'required|string|email|max:100|unique:users,email,'.$this->route('applicant'),
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
            'state'=>[
                'required',
                'integer',
                Rule::exists('states','id')->where(function($query){
                    $query->where('country_id',$this->input('country'));
                }),
            ],
            'country'=>'required|integer|exists:countries,id',
            'postal_code'=>'nullable|string|max:8',
            'phone'=>'required|string|max:20',
            'secondary_phone'=>'nullable|string|max:20',
        ];
    }
}
