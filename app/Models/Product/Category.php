<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bkwld\Croppa\Facade as Croppa;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable=['name','parent_id','status','image','details','slug'];
    protected $casts=['details'=>'json'];

    protected $appends=['thumbnail'];

    public function equipments(){
    	return $this->belongsToMany(Equipment::class);
    }

    public function parent(){
    	return $this->belongsTo(Category::class);
    }

    public function childs(){
    	return $this->hasMany(Category::class,'parent_id');
    }

    public function getStatusName(){
        $status = $this->attributes['status'];

        if($status == 1)
            return __("Active");
        elseif ($status == 2)
            return __("Inactive");

        return '-';

    }

    public function getSlugAttribute(){
    	if (!$this->attributes['slug']){
    		return str_slug($this->attributes['name']);
	    }
	    return $this->attributes['slug'];
    }

    public function setSlugAttribute($value){
	    $this->attributes['slug']=str_slug(strtolower($value));
    }

	public function scopeActive($query){
		return $query->where(function($sub_where){
			$sub_where->where('status',1)->orWhere('status',0);
		});
	}

	/**
	 * @return bool true if this and all it's parents are active.
	 *
	 */
	public function getIsActiveAttribute(){
    	$current_category=$this;
    	while($current_category){
    		if($current_category->status==2){
    			return false;
		    }
		    $current_category=$current_category->parent;
	    }
	    return true;
	}


	public function getImagePathAttribute(){
		if (isset($this->details['image'])){
			$img= $this->details['image'];
			return str_replace('/storage/','',$img)?$img:'';
		}
		return '';
	}

	public function getThumbnailAttribute(){
		$image = $this->getImagePathAttribute();
		if($image){
			$url = Croppa::url($image,350,150,['resize','pad']);
			return str_replace('%20',' ',$url);
		}
		return $image;
	}

}
