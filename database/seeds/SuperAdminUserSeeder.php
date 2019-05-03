<?php

use Illuminate\Database\Seeder;

class SuperAdminUserSeeder extends Seeder
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
			'first_name'=>'admin',
			'last_name'=>'admin',
			'phone'=>'1234567890',
			'country_id'=>\App\Models\Geo\Country::where('iso_code','CAN')->first()->id,
			'settings'=>'{}',
			'email'=>'admin@admin.com',
			'password'=>'1234'
		]);
		$admin->save();
		$admin->rols()->save(\App\Models\Role::where('name','superadmin')->first()); //2 is the id for admin

    }
}
