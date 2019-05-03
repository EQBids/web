<?php

namespace App\Http\Requests\Buyer\Billing;

use Illuminate\Foundation\Http\FormRequest;

class billingCURequest extends billingIndexRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $parent = parent::rules();
        $parent['name']='required|string|max:100';
        $parent['address']='required|string|max:200';
	    $parent['notes']='nullable|string|max:200';

        return $parent;
    }
}
