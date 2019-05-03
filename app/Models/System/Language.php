<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable=['iso_code','status','name'];
}
