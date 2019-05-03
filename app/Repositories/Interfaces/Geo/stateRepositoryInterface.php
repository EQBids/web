<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 6:36 PM
 */

namespace App\Repositories\Interfaces\Geo;


interface stateRepositoryInterface extends CountryRepositoryInterface {

    /**
     * Returns all the states of a given country.
     * @param $country_id
     * @return mixed
     */
    function findByCountry($country_id);

	function paginateByName($name,$perpage,array $columns=['*'], $country_id=null);

}