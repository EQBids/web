<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 6:37 PM
 */

namespace App\Repositories\Eloquent\Geo;


use App\Models\Geo\City;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use Faker\Provider\Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class cityRepository extends BaseRepository implements cityRepositoryInterface {

	public function __construct() {
		BaseRepository::__construct(new City());
	}

    /**
     * Returns all the cities of a given state/province.
     * @param $state_id
     * @return mixed
     */
    public function findByState($state_id)
    {
        return $this->findAllBy($state_id,'state_id');
    }

	function paginateByName( $name, $perpage,array $columns = [ '*' ], $country_id=null, $state_id=null,$metro_id=null ) {

    	$country_id=intval($country_id);
		$state_id=intval($state_id);
		$metro_id=intval($metro_id);

		$query=$this->model->query();
		if ($name){
			$query=$query->where(DB::raw('lower(name)'),'like', '%'.strtolower($name).'%');
		}

		if ($country_id && is_integer($country_id)){
			$query=$query->where('country_id',$country_id);
		}

		if ($state_id && $country_id && is_integer($state_id)){
			$query=$query->where('state_id',$state_id);
		}

		if ($state_id && $metro_id && is_integer($metro_id)){
			$query=$query->where('metro_id',$metro_id);
		}

		return $query->paginate($perpage)->appends(Input::except(['page']));

	}

	/**
	 * Returns all the states of a given country.
	 * @param $country_id
	 * @return mixed
	 */
	public function findByCountry($country_id)
	{
		return $this->findAllBy($country_id,'country_id');
	}
}