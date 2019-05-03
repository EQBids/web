<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/13/18
 * Time: 7:40 AM
 */

namespace App\Repositories\Interfaces\Geo;


interface metroRepositoryInterface extends CountryRepositoryInterface {

	function paginateByName($name,$perpage,array $columns=['*'], $country_id=null, $state_id=null);
}