<?php

namespace App\Http\Requests\Admin\Equipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasAnyRol(['superadmin','admin','staff']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          =>          'required|max:50',
            'category'      =>          'required|integer|exists:categories,id',
            'brand'         =>          'nullable|integer|exists:brands,id',
            'image'         =>          'required|mimes:jpeg,jpg,png|file|max:2048',
	        'description'   =>          'nullable|string|max:10000',
            'status'            =>       'required|integer|in:0,1',
            'excerpt'           =>      'nullable|string',
            'allow_attachments' =>      'nullable|boolean'

        ];
    }
}
