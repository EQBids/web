<?php

use Illuminate\Database\Seeder;

class initialIndustries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Industry::insert(
        	[
	            ['name'=>'Construction - Commercial/Industrial'],
		        ['name'=>'Construction - Residential, Manufacturing'],
		        ['name'=>'Energy, Government'],
		        ['name'=>'Other']
	        ]
        );
    }
}
