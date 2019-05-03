<?php

namespace App\Models\Supplier;

use App\Models\Product\Equipment;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable=['details','status','equipment_id','supplier_id'];

    protected $casts = [
        'details'=>'json'
    ];
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function equipment(){
    	return $this->belongsTo(Equipment::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }

}
