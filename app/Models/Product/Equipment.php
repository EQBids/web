<?php

namespace App\Models\Product;

use App\Models\Supplier\Inventory;
use App\Observers\EquipmentObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\EquipmentType;
use App\Models\Product\Brand;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use Bkwld\Croppa\Facade as Croppa;

class Equipment extends Model
{
    use SoftDeletes,Notifiable;
	protected $table='equipments';

	protected $dispatchesEvents = [
	    'deleting'  =>  EquipmentObserver::class,
    ];

	protected $fillable=['name','status','details','brand_id','email_cost_code','bid_cost_code'];
	protected $casts=[
		'details'=>'json'
	];

	public function brand(){
		return $this->belongsTo(Brand::class);
	}

	public function types(){
		return $this->belongsToMany(EquipmentType::class);
	}

	public function inventories(){
		return $this->hasMany(Inventory::class);
	}

	public function categories(){
		return $this->belongsToMany(Category::class);
	}

	public function scopeWithSupplierInRange(Builder $query,$lat,$lon,$country_id,$earthRadius=6378.388){
		$limits = coordinatesBoundaries($lat,$lon,$radius);
		
		return $query->whereExists(function ($inventories_exists) use ($lat,$limits,$lon,$earthRadius,$radius,$country_id){
			$inventories_exists->select(DB::raw(1))
               ->from('inventories')
               ->whereRaw('equipments.id = inventories.equipment_id')
				->whereExists(function ($suppliers_exists) use ($lat,$limits,$lon,$earthRadius,$radius,$country_id){
					$suppliers_exists->select(['lat','lon'])
		                 ->from('suppliers')
		                 ->whereRaw('inventories.supplier_id = suppliers.id')
						->whereBetween('suppliers.lat',[$limits['min_lat'],$limits['max_lat']])
						->whereBetween('suppliers.lon',[$limits['min_lng'],$limits['max_lng']])
						->havingRaw('GeoDist(?,?,`suppliers`.`lat`,`suppliers`.`lon`,?) <= ?',[$lat,$lon,$earthRadius,$radius]);
					if ($country_id){
						$suppliers_exists->where('country_id',$country_id);
					}
					$suppliers_exists->where('suppliers.status',1);
				})->where('inventories.status',1);
		})->where('equipments.status',1);
	}

	public function scopeActive($query){
		return $query->where('status',1);
	}


	public function getDescriptionAttribute(){
		if (!isset($this->details['description'])){
			return '';
		}
		return Purifier::clean(html_entity_decode($this->attributes['details']['description']));
	}

	public function getImagePathAttribute(){
		if (isset($this->details['image'])){
			return $this->details['image'];
		}
		return '';
	}

	public function getStatusName(){
		$status = $this->attributes['status'];

		if($status == 1)
			return __("Active");
		elseif ($status == 0)
			return __("Inactive");

		return '-';

	}

	/**
	 * @return bool true if it's category and all it's parents are active.
	 *
	 */
	public function getIsActiveAttribute(){
		$current_category=$this->categories()->first();
		while($current_category){
			if($current_category->status==2){
				return false;
			}
			$current_category=$current_category->parent;
		}
		return true;
	}

	public function getThumbnailAttribute(){
		$image = $this->getImagePathAttribute();
		if($image){
			$url = Croppa::url($image,160,160,['resize','pad']);
			return str_replace('%20',' ',$url);
		}
		return $image;
	}

}
