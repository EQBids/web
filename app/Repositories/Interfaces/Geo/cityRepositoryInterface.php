<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 6:36 PM
 */

namespace App\Repositories\Interfaces\Geo;


interface cityRepositoryInterface extends CountryRepositoryInterface {

    /**
     * Returns all the cities of a given state/province.
     * @param $state_id
     * @return mixed
     */
    public function findByState($state_id);

	function paginateByName($name,$perpage,array $columns=['*'], $country_id=null, $state_id=null,$metro_id=null);

}