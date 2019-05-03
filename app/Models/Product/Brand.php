<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable=['name','status','details'];
    protected $casts=[
    	'details'=>'json'
    ];


    public function equipments(){
    	return $this->hasMany(Equipment::class);
    }
}
