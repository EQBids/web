<?php

use Illuminate\Database\Seeder;

class defaultMetroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Geo\Metro::create([
        	'name'=>'Greater Toronto Area',
	        'status'=>0,
	        'lat'=>43.638830778,
	        'lon'=>-79.385665124,
	        'country_id'=>\App\Models\Geo\Country::where('iso_code','CAN')->first()->id,
	        'state_id'=>\App\Models\Geo\State::where('iso_code','CA-ON')->first()->id,
        ]);
    }
}
