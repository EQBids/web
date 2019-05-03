<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 6:37 PM
 */

namespace App\Repositories\Eloquent\Geo;


use App\Models\Geo\State;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class stateRepository extends BaseRepository implements stateRepositoryInterface {

	public function __construct() {
		parent::__construct(new State());
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

	function paginateByName( $name, $perpage,array $columns = [ '*' ],$country_id=null ) {
    	$country_id=intval($country_id);
		$query=$this->model->query();
		if ($name){
			$query=$query->where(DB::raw('lower(name)'),'like', '%'.strtolower($name).'%');
		}
		if ($country_id && is_integer($country_id)){
			$query=$query->where('country_id',$country_id);
		}
		return $query->paginate($perpage)->appends(Input::except(['page']));

	}

}