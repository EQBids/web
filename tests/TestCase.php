<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    function assertApiSuccess(TestResponse $response){
		$data = $response->decodeResponseJson();
		self::assertEquals(200,$response->getStatusCode());
	    self::assertArrayHasKey('error',$data);
	    self::assertEquals(0,$data['error']);
    }


	function assertApiError(TestResponse $response){
		$data = $response->decodeResponseJson();
		self::assertArrayHasKey('error',$data);
		self::assertEquals(1,$data['error']);
		self::assertArrayHasKey('error_message',$data);
	}

}
