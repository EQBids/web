<?php

namespace App\Http\Requests\Buyer\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class detailsRequest extends FormRequest
{
    /**
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
		return [
            'equipments'=>'required|array',
	        'equipments.*.id'=>'required|integer|exists:equipments,id',
	        'equipments.*.from'=>'required|date|after_or_equal:yesterday', //TODO: this should be today instead, but we need to define a way to properly handle timezones
	        'equipments.*.to'=>'required|date|after_or_equal:equipments.*.from',
	        'equipments.*.qty'=>'required|integer|min:0|max:1000',
			'equipments.*.notes'=>'nullable|string|max:1000'
        ];
    }

	public function messages() {
		return [
			'equipments.*.from.after_or_equal'=>'The :attribute must be a date after or equal to today',
		];
	}
}
