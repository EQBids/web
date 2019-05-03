<?php

namespace App\Repositories\Interfaces\Buyer;


use App\Repositories\Interfaces\baseRepositoryInterface;

interface billingRepositoryInterface extends baseRepositoryInterface {

	public function paginateBy( $perPage,$value,$column='id', array $columns = ['*'] );

}