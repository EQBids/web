<?php

namespace App\Http\Requests\Admin\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class baseReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from'=>'nullable|date',
	        'to'=>'nullable|date',
	        'country_id'=>'nullable|integer|exists:countries,id',
            'state_id'=>'nullable|integer|exists:states,id',
            'city_id'=>'nullable|integer|exists:cities,id',
	        'industry_id'=>'nullable|integer|exists:industries,id',
	        'supplier_id'=>'nullable|integer|exists:suppliers,id',
	        'equipment_id'=>'nullable|integer|exists:equipments,id'
        ];
    }
}
