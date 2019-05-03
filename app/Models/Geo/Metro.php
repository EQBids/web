<?php

namespace App\Models\Geo;

use App\Models\Buyer\Contractor;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\Buyer\Site;
use App\User;

class Metro extends Model
{
    protected $fillable=['status','name','lat','lon','state_id','country_id'];

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function state(){
    	return $this->belongsTo(State::class);
    }

	public function cities(){
		return $this->hasMany(City::class);
	}

	public function suppliers(){
		return $this->hasMany(Supplier::class);
	}

	public function sites(){
		return $this->hasMany(Site::class);
	}

	public function users(){
		return $this->hasMany(User::class);
	}

	public function contractors(){
		return $this->hasMany(Contractor::class);
	}

}
