<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable=['name','parent_id'];

    public function parent(){
    	return $this->belongsTo(Industry::class,'parent_id');
    }

    public function childs(){
    	return $this->hasMany(Industry::class,'parent_id');
    }

    public function scopeTopOnly($query){
    	return $query->where('parent_id',null);
    }


}
