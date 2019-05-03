<?php

namespace App\Models\Buyer;

use App\Models\Billing;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Metro;
use App\Models\Geo\State;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{

	use SoftDeletes;

    protected $fillable=['nickname','name','lat','lon','address','details','contractor_id','metro_id','city_id','state_id','country_id','zip','phone','contact'];
    protected $casts=[
    	'details'=>'json'
    ];

    use SoftDeletes;

    protected $hidden=['created_at','updated_at'];

    public function contractor(){
    	return $this->belongsTo(Contractor::class);
    }

    public function metro(){
    	return $this->belongsTo(Metro::class);
    }

    public function city(){
    	return $this->belongsTo(City::class);
    }

	public function orders(){
		return $this->hasMany(Order::class);
	}

	public function billing(){
    	return $this->belongsTo(Billing::class);
	}

	public function state(){
    	return $this->belongsTo(State::class);
	}

	public function country(){
    	return $this->belongsTo(Country::class);
	}

	public function scopeInRange(Builder $query,$lat,$lon,$radius,$earth=6378.388){

    	return $query->selectRaw('GeoDist(?,?,sites.lat,sites.lon,?) as site_distance')
	                 ->having('distance','<=',$radius)
	                 ->setBindings([$lat,$lon,$earth], 'select');
	}

	public function users(){
    	return $this->belongsToMany(User::class);
	}
}


