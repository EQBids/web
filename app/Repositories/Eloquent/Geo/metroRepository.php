<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/13/18
 * Time: 7:41 AM
 */

namespace App\Repositories\Eloquent\Geo;


use App\Models\Geo\Metro;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Geo\metroRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class metroRepository extends BaseRepository implements metroRepositoryInterface {

	public function __construct() {
		parent::__construct(new Metro());
	}

	public function findByCountry($country_id)
	{
		return $this->findAllBy($country_id,'country_id');
	}

	function paginateByName( $name, $perpage,array $columns = [ '*' ],$country_id=null,$state_id=null) {

		$country_id=intval($country_id);
		$state_id=intval($state_id);

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

		return $query->paginate($perpage)->appends(Input::except(['page']));

	}
}