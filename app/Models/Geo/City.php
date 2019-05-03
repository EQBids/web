<?php

namespace App\Models\Geo;

use App\Models\Buyer\Contractor;
use App\Models\Buyer\Site;
use App\Models\Supplier\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable=['name','status','lat','lon','state_id','metro_id','country_id'];
	protected $hidden=['created_at','updated_at'];

    public function state(){
    	return $this->belongsTo(State::class);
    }

    public function metro(){
    	return $this->belongsTo(Metro::class);
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    }

	public function suppliers(){
		return $this->hasMany(Supplier::class);
	}

	public function contractors(){
    	return $this->hasMany(Contractor::class);
	}

	public function sites(){
    	return $this->hasMany(Site::class);
	}

	public function users(){
    	return $this->hasMany(User::class);
	}
}
