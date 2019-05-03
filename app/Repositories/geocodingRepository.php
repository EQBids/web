<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/14/18
 * Time: 6:40 PM
 */

namespace App\Repositories;
use App\Models\Geo\City;
use Geocoder\Laravel\Facades\Geocoder;

use App\Repositories\Interfaces\geocodingRepositoryInterface;
use Geocoder\Model\Coordinates;

class geocodingRepository implements geocodingRepositoryInterface {

	function getAddressCoordinates( $address, City $city=null ) {
		if($address){
			$results=Geocoder::geocode($address)->get();
			//multiple results could be returned by the api, choose the first valid one:
			foreach ($results as $result_address){
				if ($result_address->getCoordinates()){
					return $result_address->getCoordinates();
				}
			}
		}
		if($city){
			return new Coordinates($city->lat,$city->lon);
		}
		return null;
	}

}