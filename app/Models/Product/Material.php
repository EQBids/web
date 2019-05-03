<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

	protected $fillable=['name','status','details','brand_id','email_cost_code','bid_cost_code'];
	protected $casts=[
		'details'=>'json'
	];

	public function categories(){
		return $this->belongsToMany(Category::class);
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
