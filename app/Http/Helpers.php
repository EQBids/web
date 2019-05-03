<?php

namespace App\Http;
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 2/22/18
 * Time: 2:38 PM
 */



function getTestUrl($url){
	return env('TEST_API_BASE_HOST','').$url;
}

function getTestApiAuth($test){
	$response = $test->post(getTestUrl('/oauth/token'),[

		'grant_type' => 'password',
		'client_id' => env('TEST_API_CLIENT_ID',''),
		'client_secret' => env('TEST_API_CLIENT_SECRET',''),
		'username' => env('TEST_API_CLIENT_USERNAME',''),
		'password' => env('TEST_API_CLIENT_PIN',''),
		'scope' => '',

	]);
	return $response->decodeResponseJson();

}

function getTestApiAdminAuth($test){
	$response = $test->post(getTestUrl('/oauth/token'),[

		'grant_type' => 'password',
		'client_id' => env('TEST_API_CLIENT_ID',''),
		'client_secret' => env('TEST_API_CLIENT_SECRET',''),
		'username' => env('TEST_API_CLIENT_ADMIN',''),
		'password' => env('TEST_API_CLIENT_ADMIN_PIN',''),
		'scope' => '',

	]);
	return $response->decodeResponseJson();
}

function customRequestCaptcha(){
    return new \ReCaptcha\RequestMethod\Post();
}

/**
 * this function calculates the boundaries to optimize the search of lat/lon points  that are in
 * a distance lower than $distance of the point $lat/lng.
 *
 * this function was extracted and modified from: http://www.michael-pratt.com/blog/7/Encontrar-Lugares-cercanos-con-MySQL-y-PHP/
 *
 * @param $lat latitude of the centre point
 * @param $lng longitude of the centro point
 * @param int $distance radius of the area of interest
 * @param int $earthRadius the default value (6371) means that the distance is in meters, set to 3959 if miles are desired
 *
 * @return array
 *
 */
function coordinatesBoundaries($lat, $lng, $distance = 1, $earthRadius = 6378.388) {
	$return = array();

	// Los angulos para cada dirección
	$cardinalCoords = array('north' => '0',
	                        'south' => '180',
	                        'east' => '90',
	                        'west' => '270');
	$rLat = deg2rad($lat);
	$rLng = deg2rad($lng);
	$rAngDist = $distance / $earthRadius;
	foreach ($cardinalCoords as $name => $angle) {
		$rAngle = deg2rad($angle);
		$rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
		$rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));
		$return[$name] = array('lat' => (float) rad2deg($rLatB),
		                       'lng' => (float) rad2deg($rLonB));
	}
	return array('min_lat' => $return['south']['lat'],
	             'max_lat' => $return['north']['lat'],
	             'min_lng' => $return['west']['lng'],
	             'max_lng' => $return['east']['lng']);
}

function buildAddress(String $address,String $country,String $state,String $city, String $metro = null): String {

    $fullAddress = $address.", ".$city;

    if($metro){
        $fullAddress.= ", ".$metro;
    }

    $fullAddress.= ", ".$state.", ".$country;

    return $fullAddress;
}

function getEmailsFromCommaList(String $emails): array {

    try{
        return explode(",",$emails);
    }catch (\Exception $e){
        return [];
    }

}