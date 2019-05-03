<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{

    protected $redirectRoute = "contact";

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
            'name'=>'required|string|max:100|min:8',
            'email'=>'required|string|email|max:100',
            'telephone-day'=>'nullable|string|max:20',
            'telephone-night'=>'nullable|string|max:20',
            'company_name'=>'nullable|string|max:100',
            'message'=>'required|string|max:250|min:50',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
