<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class apiBasicTest extends TestCase
{
    /**
     * test the api login process
     *
     * @return void
     */
    public function testLogin()
    {

    	//correct login
    	$response = getTestApiAuth($this);
    	$this->assertArrayHasKey('access_token',$response);

    	//should not login
	    $response = $this->post(getTestUrl('/oauth/token'),[

		    'grant_type' => 'password',
		    'client_id' => env('TEST_API_CLIENT_ID',''),
		    'client_secret' => env('TEST_API_CLIENT_SECRET',''),
		    'username' => 'fake@test.com',
		    'password' => '1234',
		    'scope' => '',

	    ]);
	    $response=$response->decodeResponseJson();

	    $this->assertArrayNotHasKey('access_token',$response);
    }

    public function testSecurity(){
		//test unauthorized access
	    $response = $this->get(getTestUrl('/api/user'),[
	    	'Accept'=>'application/json'
	    ]);
	    $this->assertEquals(401,$response->getStatusCode());

	    //test passport authorization, should no be able to get any content
	    $token = getTestApiAuth($this);
	    $token=$token['access_token'];
	    $response = $this->get(getTestUrl('/api/user'),[
	    	'Accept'=>'application/json',
		    'Authorization'=>'Bearer '.$token
	    ]);

	    $this->assertApiError($response);
	    $this->assertEquals('you credentials are not enough',$response->json()['error_message']);

    }

    public function testRoleAuth(){
	    //admin auth, should works
	    $AdminAuth = getTestApiAdminAuth($this);
	    $adminToken = $AdminAuth['access_token'];

	    $adminResponse = $this->get(getTestUrl('/api/user'),[
		    'Accept'=>'application/json',
		    'Authorization'=>'Bearer '.$adminToken
	    ]);
	    $this->assertApiSuccess($adminResponse);
    }



}
