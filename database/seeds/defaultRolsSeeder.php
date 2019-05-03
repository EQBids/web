<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class defaultRolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**  NOTE: 17/03/2018
         *   this seeder has been modified to replace the old roles with the new one
         * */
        $olds = \App\Models\Role::all()->mapWithKeys(function (Role $role){
        	return [$role->name => $role ];
        });

        if(isset($olds['system'])){
        	unset($olds['system']);
        }else{
        	\App\Models\Role::insert([
        		[
        			'name'=>'system'

		        ]
	        ]);
        }

        if (isset($olds['super-admin'])){
	        $olds['super-admin']->name='superadmin';
	        $olds['super-admin']->save();
	        unset($olds['super-admin']);
        }elseif(isset($olds['superadmin'])){
        	unset($olds['superadmin']);
        } else{
	        $superadmin = new \App\Models\Role();
	        $superadmin->name='superadmin';
	        $superadmin->save();
        }

	    if(isset($olds['admin'])){
		    unset($olds['admin']);
	    }else{
		    \App\Models\Role::insert([
			    [
				    'name'=>'admin'
			    ]
		    ]);
	    }


	    //deletes all the previous roles
	    $oldIds = $olds->map(function ($item,$key){
	    	return $item->id;
	    })->values();
		\App\Models\RoleUser::whereIn('role_id',$oldIds)->delete();
	    Role::destroy($oldIds);



        //insert the new ones
	    $newones=\App\Models\Role::insert(
		    [
			    [
				    'name'=>'staff'
			    ],
			    [
				    'name'=>'contractor-superadmin'
			    ],
			    [
				    'name'=>'contractor-admin'
			    ],
			    [
				    'name'=>'contractor-admin'
			    ],
			    [
				    'name'=>'contractor-worker'
			    ],
			    [
				    'name'=>'contractor-worker'
			    ],
			    [
				    'name'=>'supplier-admin'
			    ],
			    [
				    'name'=>'supplier-manager'
			    ],
			    [
				    'name'=>'supplier-salesperson'
			    ],
		    ]
	    );
	    
    }
}
