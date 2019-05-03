<?php

namespace App\Models\Buyer;

use App\Models\Billing;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Metro;
use App\Models\Geo\State;
use App\Models\Industry;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Buyer\Site;
use App\Models\Buyer\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contractor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'company_name',
        'address',
        'lat',
        'lon',
        'details',
        'user_id',
        'country_id',
        'state_id',
        'city_id',
        'metro_id',
        'postal_code',
	    'industry_id'
    ];

    protected $casts=[
    	'details'=>'json'
    ];

	public function sites(){
		return $this->hasMany(Site::class);
	}

	public function orders(){
		return $this->hasMany(Order::class);
	}

	public function billings(){
		return $this->hasMany(Billing::class);
	}

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function country(){
		return $this->belongsTo(Country::class);
	}

	public function state(){
		return $this->belongsTo(State::class);
	}

	public function metro(){
		return $this->belongsTo(Metro::class);
	}

	public function city(){
		return $this->belongsTo(City::class);
	}

	public function users(){
		return $this->belongsToMany(User::class);
	}

	public function industry(){
		return $this->belongsTo(Industry::class);
	}
}
