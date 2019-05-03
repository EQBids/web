<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable=['timestamp','description','error_code'];
}
