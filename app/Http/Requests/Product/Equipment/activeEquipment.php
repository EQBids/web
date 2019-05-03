<?php

namespace App\Http\Requests\Product\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class activeEquipment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    if($this->route('equipment')){
		    return $this->route('equipment')->is_active; //active
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
