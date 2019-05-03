<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 5:55 PM
 */

namespace App\Repositories\Eloquent\Geo;


use App\Models\Geo\Country;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


class CountryRepository extends BaseRepository implements CountryRepositoryInterface {

	public function __construct() {

		$model = Country::class;
		parent::__construct(new $model);
	}

	function paginateByName( $name, $perpage,array $columns = [ '*' ] ) {

		$query=$this->model->query();
		if ($name){
			$query=$query->where(DB::raw('lower(name)'),'like', '%'.strtolower($name).'%');
		}
		return $query->paginate($perpage)->appends(Input::except(['page']));

	}
}