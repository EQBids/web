<?php

namespace App\Http\Requests\Buyer\Order;

use App\Models\Buyer\Site;
use App\Rules\supplierInRange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class supplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->is_contractor
               && Auth::user()->cart && isset(Auth::user()->cart->details['site_id']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	$cart = Auth::user()->cart;
	    $location = $cart->details['site_id'];
	    $settings_repository=app('App\Repositories\Eloquent\SettingsRepository');
	    $radius = $settings_repository->getValue('radius_in_km_from_site',100);
		$location = Site::find($location);
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
