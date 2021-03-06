<?php

namespace App\Models\Geo;

use App\Models\Buyer\Contractor;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\User;

class State extends Model
{

	protected $fillable=['status','iso_code','name'];
	protected $hidden=['created_at','updated_at'];


	public function country(){
		return $this->belongsTo(Country::class);
	}

	public function metros(){
		return $this->hasMany(Metro::class);
	}

	public function cities(){
		return $this->hasMany(City::class);
	}

	public function suppliers(){
		return $this->hasMany(Supplier::class);
	}

	public function users(){
		return $this->hasMany(User::class);
	}

	public function contractors(){
		return $this->hasMany(Contractor::class);
	}
}
