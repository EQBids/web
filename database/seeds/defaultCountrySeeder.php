<?php

use Illuminate\Database\Seeder;

class defaultCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('countries')->insert([
        	'name'=>'Canada',
	        'iso_code'=>'CAN',
	        'status'=>0
        ]);
    }
}
