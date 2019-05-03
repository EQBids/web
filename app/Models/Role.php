<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

	protected $fillable=['name'];

	protected $hidden=['created_at','updated_at','pivot'];

	protected $table='roles';

	function users(){
		return $this->belongsToMany(Role::class)->using(RoleUser::class);
	}


    function getIntuitiveNameAttribute(){

	    $roleName = strtolower($this->attributes['name']);
	    $name = '';
	    switch ($roleName){
            case 'contractor-superadmin':{
                $name = 'Regional manager';
                break;
            }

            case 'contractor-admin':{
                $name = 'Office manager';
                break;
            }

            case 'contractor-manager':{
                $name = 'Job site manager';
                break;
            }

            case 'contractor-worker':{
                $name = ' Job site worker';
                break;
            }

            default:{
                $name = '';
                break;
            }
        }

        return $name;
    }

}
