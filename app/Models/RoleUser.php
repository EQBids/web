<?php

namespace App\Models;

use App\Models\Buyer\Contractor;
use App\Models\Supplier\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table='role_user';

    protected $fillable=['role_id','user_id','supplier_id','contractor_id'];

    public function role(){
    	return $this->belongsTo(Role::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function supplier(){
    	return $this->belongsTo(Supplier::class);
    }

    public function contractor(){
    	return $this->belongsTo(Contractor::class);
    }

}
