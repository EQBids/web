<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class defaultCitySeeder extends Seeder
{

	public function __construct() {
	}

	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $country = \App\Models\Geo\Country::where('iso_code','CAN')->first();
	    $state = \App\Models\Geo\State::where('iso_code','CA-ON')->first();
	    $file = fopen('resources/assets/seeds-data/on-cities.txt','r') or die("Unable to open file!");
	    $geoRepository = new \App\Repositories\geocodingRepository();
	    $now = Carbon::now('utc')->toDateTimeString();
	    $newCities=[];
	    while(($line = fgets($file)) !== false){
	    	$line=trim($line);
	    	$exists = DB::table('cities')->where(DB::raw('lower(name)'),'=',strtolower($line))->count();
	    	if(!$exists){
	    		$coordinates = $geoRepository->getAddressCoordinates($line.', '.$state->name.', '.$country->name);
	    		if (!$coordinates){
				    $coordinates = $geoRepository->getAddressCoordinates($line.', '.$country->name);
			    }
			    if (!$coordinates){
	    			echo('Skipped: '.$line.PHP_EOL);
	    			continue;
			    }
				array_push($newCities,[
					'name'=>$line,
					'lat'=>$coordinates->getLatitude(),
					'lon'=>$coordinates->getLongitude(),
					'status'=>0,
					'country_id'=>$country->id,
					'state_id'=>$state->id,
					'created_at'=>$now
				]);
		    }
	    }
	    \App\Models\Geo\City::insert($newCities);
	    /*
	    $city = \App\Models\Geo\City::create([
	    	'name'=>'Toronto',
		    'lat'=>43.761539,
		    'lon'=>-79.411079,
		    'status'=>0,
		    'country_id'=>$country->id,
		    'state_id'=>$state->id
	    ]);*/
    }
}
