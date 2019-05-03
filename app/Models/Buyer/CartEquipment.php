<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartEquipment extends Pivot
{

    protected $fillable=['cart_id','equipment_id','to','from','qty','extras'];
	protected $dates=['to','from'];

	protected $casts=[
		'extras'=>'json'
	];


}
