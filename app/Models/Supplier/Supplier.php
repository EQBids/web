<?php

namespace App\Models\Supplier;

use function App\Http\coordinatesBoundaries;
use App\Models\Buyer\Order;
use App\Models\Geo\City;
use App\Models\Geo\Country;
use App\Models\Geo\Metro;
use App\Models\Geo\State;
use App\Models\OrderSupplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable=['name','address','lat','lon','details',
	    'status','country_id','state_id','city_id','metro_id'];

    protected $casts=[
    	'details'=>'json'
    ];

    protected static function boot() {
	    parent::boot();
	    static::addGlobalScope('active',function (Builder $builder){
	    	$builder->where('suppliers.status',1);
	    });
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

	public function inventories(){
		return $this->hasMany(Inventory::class);
	}

	public function bid(){
		return $this->hasMany(Bid::class);
	}

	public function scopeInRange(Builder $query,$radius,$lat,$lon,$country_id,$earthRadius=6378.388){
    	$query->selectRaw('suppliers.*, GEODIST(?,?,`suppliers`.`lat`,`suppliers`.`lon`) as distance ',[$lat,$lon])
		      ->whereRaw('GEODIST(?,?,`suppliers`.`lat`,`suppliers`.`lon`) <= 200 ',[$lat,$lon]);
		if ($country_id){
			$query->where('country_id',$country_id);
		}
	}

	public function orders(){
		return $this->belongsToMany(Order::class)->withPivot(['status'])->using(OrderSupplier::class);
	}

}
