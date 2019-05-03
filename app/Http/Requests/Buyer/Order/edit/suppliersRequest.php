<?php

namespace App\Http\Requests\Buyer\Order\edit;

use App\Http\Requests\Buyer\Order\supplierRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\supplierInRange;

class suppliersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    if(Auth::check() && Auth::user()->is_contractor){
		    $order = $this->route('order');
		    if($order) return $order->is_editing;
	    }
	    return false;
    }


	public function rules()
	{
		$order = $this->route('order');
		$order_repository=app('App\Repositories\Eloquent\Buyer\orderRepository');
		$location = $order_repository->getEditingSite($order);
		$settings_repository=app('App\Repositories\Eloquent\SettingsRepository');
		$radius = $settings_repository->getValue('radius_in_km_from_site',100);
		return [
			'suppliers'=>['required','array','min:1'],
			'suppliers.*'=>['required','integer',
				new supplierInRange(
					$location->lat,
					$location->lon,
					$radius,
					$location->country_id
				)
			]
		];
	}

}
