<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class initialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$toInsert = [
		    [
			    'name'=>'pin_expires_in_secs',
			    'value'=>3600
		    ],
		    [
		    	'name'=>'cart',
			    'value'=>[
			        'radius'=>160
                ]
		    ]
	    ];
        foreach ($toInsert as $value){
        	$encodedValue = json_encode($value['value']);
        	if(!$encodedValue){
        		$encodedValue=$value;
	        }
	        $value['value']=$encodedValue;
        	if(DB::table('settings')->where('name',$value['name'])->select('*')->count()==0){
        		DB::table('settings')->insert($value);
	        }else{
        		DB::table('settings')->where('name',$value['name'])->update($value);
	        }
        }
    }
}
