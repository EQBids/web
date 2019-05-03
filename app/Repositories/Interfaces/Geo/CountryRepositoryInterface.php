<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 6:21 PM
 */

namespace App\Repositories\Interfaces\Geo;


use App\Repositories\Interfaces\baseRepositoryInterface;

interface CountryRepositoryInterface extends baseRepositoryInterface {


	function paginateByName($name,$perpage,array $columns=['*']);

}