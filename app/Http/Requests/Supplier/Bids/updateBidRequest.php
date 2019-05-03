<?php

namespace App\Http\Requests\Supplier\Bids;

use App\Models\Buyer\OrderItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class updateBidRequest extends editableBidRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
	public function rules()
	{

		return [
			'equipments'=>'required|array',
			'equipments.*.id'=>['required','integer',
				Rule::exists('order_items','id')
				    ->where('order_id',$this->route('bid')->order_id)
			],
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
			if(isset($equipment['pick'])){
				$equipment['pick']=preg_replace('/[^\d|.]/', '',$equipment['pick']);
			}
			if(isset($equipment['delivery'])){
				$equipment['delivery']=preg_replace('/[^\d|.]/', '',$equipment['delivery']);
			}


		}
		$this->merge(['equipments'=>$equipments]);
	}
}
