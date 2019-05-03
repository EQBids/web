<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/5/18
 * Time: 8:26 AM
 */

namespace App\Repositories\Eloquent;


use App\Repositories\Interfaces\baseRepositoryInterface;
use App\Repositories\Interfaces\CriteriaInterface;
use App\Repositories\Interfaces\Paginator;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements baseRepositoryInterface {


	protected $model;
	protected $query;
	protected $page=null;

	public function __construct(Model $model) {
		$this->model=$model;
		$this->query=$this->model->query();
	}


	public function findOneBy( $value = null, $field = 'id', array $columns = [ '*' ] ) {
		$res= $this->query->where($field,$value)->select($columns)->first();
		$this->resetScope();
		return $res;
	}

	public function findAll( array $columns = [ '*' ] ) {
		$result = $this->query->get($columns);
		$this->resetScope();
		return $result;
	}

	public function findAllBy( $value = null, $field = null, array $columns = [ '*' ], array $with=[] ) {
		if ($field==null){
			return $this->findAll();
		}
		$res= $this->query->where($field,$value)->with($with)->get($columns);
		$this->resetScope();
		return $res;
	}

	public function findAllWhereIn( array $value, $field, array $columns = [ '*' ] ) {
		if ($field == null){
			return $this->findAll($columns);
		}
		$res=$this->query->whereIn($field,$value)->get($columns);
		$this->resetScope();
		return $res;
	}

	public function with( $relations ) {
		if ($relations != null){
			$this->query->with($relations);
		}
		return $this;
	}

	public function paginate( $perPage=10, array $columns = [ '*' ] ) {
		$result = $this->query->paginate($perPage,$columns,'page',$this->page);
		$this->resetScope();
		return $result;
	}

	public function setCurrentPage( $page ) {
		$this->page=$page;
	}

	public function create( array $data ) {
		return $this->model->create($data);
	}

	public function updateBy( array $data, $value = null, $field = 'id' ) {
		$instance =$this->model->where($field,$value)->first();
		if($instance){
			$instance->fill($data);
			$instance->save();
			return $instance;
		}
		return null;

	}

	public function delete( $value = null, $field = 'id' ) {
		$this->model->where($field,$value)->delete();
		return true;
	}

	public function count() {
		return $this->query->count();
	}

	public function resetScope() {
		$this->query=$this->model->query();
	}

	public function destroy( $value = null, $field = 'id' ) {
		// TODO: Implement destroy() method.
	}

	protected function first($columns=['*']){
		$response = $this->query->first($columns);
		$this->resetScope();
		return $response;
	}

	public function withCount( $relations ) {
		$this->query->withCount($relations);
		return $this;
	}

	public function like( $column, $value ) {
		$this->query->where($column,'like', '%'.$value.'%');
		return $this;
	}
}