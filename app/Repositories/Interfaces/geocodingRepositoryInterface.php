<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/14/18
 * Time: 6:26 PM
 */

namespace App\Repositories\Interfaces;


use App\Models\Geo\City;

interface geocodingRepositoryInterface {


	/**
	 * geocodes the provided address and return it's coordinates. null if that's not posible.
	 * coordinate's precision is governed by google maps service.
	 * @param $address the address which location will be returned
	 * @param $city if city is provided and not coordinates where found. the returned coordinates will be the same specified in the city
	 *
	 * @return mixed an instance of Geocoder\Model\Coordinates that contains the closest coordinates found by google,
	 * null if wasn't posible find accurate coordinates or $address is empoty.
	 */
	function getAddressCoordinates($address, City $city=null);
}