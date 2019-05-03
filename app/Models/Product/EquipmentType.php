<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    protected $fillable=['name','status','details'];
    protected $casts=[
    	'details'=>'json'
    ];


    public function equipments(){
    	return $this->belongsToMany(Equipment::class,'equipment_type_equipment','equipment_type_id','equipment_id');
    }
}
