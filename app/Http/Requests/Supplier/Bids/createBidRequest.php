<?php

namespace App\Http\Requests\Supplier\Bids;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class createBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->is_supplier;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	return [
            'order_id'=>['required','integer',
	            Rule::exists('order_supplier','order_id')
	                ->whereIn('supplier_id',Auth::user()->suppliers->pluck('id')->toArray())],
	        'equipments'=>'required|array',
	        'equipments.*.id'=>['required','integer',Rule::exists('order_items','id')->where('order_id',$this->get('order_id'))],
	        'equipments.*.price'=>['required','numeric','min:0'],
            'equipments.*.delivery'=>['nullable','numeric','min:0'],
            'equipments.*.pick'=>['nullable','numeric','min:0'],
            'equipments.*.insurance'=>['nullable','boolean'],
            'equipments.*.from'=>'nullable|date', //TODO: this should be today instead, but we need to define a way to properly handle timezones
            'equipments.*.to'=>'nullable|date|after_or_equal:equipments.*.from',
	        'equipments.*.notes'=>'nullable|string|max:2000',
            'equipments.*.attachments'=>'nullable|array',
            'equipments.*.attachments.*'=>'nullable|string',
	        'notes'=>'nullable|string|max:10000'

        ];
    }

    protected function prepareForValidation() {
        $equipments = $this->get('equipments');
        foreach ($equipments as $id=>&$equipment){
        	if(isset($equipment['price'])){
        		$equipment['price']=preg_replace('/[^\d|.]/', '',$equipment['price']);
	        }
	        if(isset($equipment['delivery'])){
		        $equipment['delivery']=preg_replace('/[^\d|.]/', '',$equipment['delivery']);
	        }
	        if(isset($equipment['pick'])){
		        $equipment['pick']=preg_replace('/[^\d|.]/', '',$equipment['pick']);
	        }
	        
	        
        }
        $this->merge(['equipments'=>$equipments]);
    }
}
