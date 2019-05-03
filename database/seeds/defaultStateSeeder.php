<?php

use Illuminate\Database\Seeder;

class defaultStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = \App\Models\Geo\Country::where('iso_code','CAN')->first();
        $state = \App\Models\Geo\State::create([
            'iso_code'=>'CA-ON',
	        'name'=>'Ontario',
	        'status'=>0,
	        'country_id'=>$country->id
        ]);

    }
}
