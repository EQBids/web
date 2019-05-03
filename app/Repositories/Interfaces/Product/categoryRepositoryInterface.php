<?php

namespace App\Repositories\Interfaces\Product;


use App\Repositories\Interfaces\baseRepositoryInterface;

interface categoryRepositoryInterface extends baseRepositoryInterface {


    /**
     * Checks if a category has equipment or is the parent of any other category
     * @param $id
     * @return mixed
     */
    public function hasEquipmentOrCategories($id);

	/**
	 * find a category by it's slug, if the DB slug field is null name is used instead
	 * @param $slug
	 *
	 * @return mixed
	 */
    public function findOneBySlug($slug);

    public function generateCategoryOrderedList();

	public function generateCategoryAvailableOrderedList($lat,$lon,$radius,$country_id=null);

	public function active();

}