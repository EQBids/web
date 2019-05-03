<?php
namespace App\Repositories\Interfaces\Product;

use App\Repositories\Interfaces\baseRepositoryInterface;

interface equipmentRepositoryInterface extends baseRepositoryInterface {

    public function findAllGroupedByType();

    public function paginateByCategory($category,$perPage=10,$columns=['*']);

	public function paginateAvailableCategory($category,$perPage=10,$lat,$lon,$radius,$columns=['*'],$country_id=null);

	public function findOneInRange($value,$field,$lat,$lon,$radius,$columns=['*'],$country_id=null);

	public function active();
}