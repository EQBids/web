<?php

use Illuminate\Database\Seeder;

class ownerSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin= new \App\User();
		$admin->fill([
			'first_name'=>'Dave',
			'last_name'=>'Williamson',
			'country_id'=>\App\Models\Geo\Country::where('iso_code','CAN')->first()->id,
			'settings'=>'{}',
			'email'=>'davew@eqbids.com',
			'password'=>'12345'
		]);
		$admin->save();
		$admin->rols()->save(\App\Models\Role::where('name','superadmin')->first()); //2 is the id for admin

    }
}
