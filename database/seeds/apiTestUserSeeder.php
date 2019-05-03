<?php

use Illuminate\Database\Seeder;

class apiTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
        	[
        	'name'=>'api test',
	        'email'=>'api@test.com',
	        'password'=>bcrypt('1234')
	        ],
        ]);


    }
}
