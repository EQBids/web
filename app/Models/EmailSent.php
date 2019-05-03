<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSent extends Model
{
    protected $fillable=['supplier_id','order_id','details'];

    protected $casts=[
    	'details'=>'json'
    ];
}
