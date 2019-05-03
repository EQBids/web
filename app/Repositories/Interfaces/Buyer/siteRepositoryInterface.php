<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/14/18
 * Time: 8:58 AM
 */

namespace App\Repositories\Interfaces\Buyer;


use App\Repositories\Interfaces\baseRepositoryInterface;

interface siteRepositoryInterface extends baseRepositoryInterface {

	function paginateBy($filter,$perPage=10,$columns=['*']);

}