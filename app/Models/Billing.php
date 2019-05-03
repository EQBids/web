<?php

namespace App\Models;

use App\Models\Buyer\Contractor;
use App\Models\Buyer\Site;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable=['name','description','status','contractor_id'];
    protected $casts=['description'=>'json'];


    public function contractor(){
    	return $this->belongsTo(Contractor::class);
    }

    public function sites(){
    	return $this->hasMany(Site::class);
    }

}
