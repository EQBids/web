<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserStatus extends Model
{
    protected $table = 'user_status';

    protected $fillable=['user_id','device_identifier','status'];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public $timestamps = false;
}
