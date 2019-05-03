<?php

namespace App\Repositories\Eloquent\Buyer;


use App\Models\Billing;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Buyer\billingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class billingRepository extends BaseRepository implements billingRepositoryInterface {

	public function __construct( Billing $model ) {
		parent::__construct($model);
	}

	public function paginateBy( $perPage, $value, $column = 'id', array $columns = [ '*' ] ) {
		return $this->query->where($column,$value)->paginate($perPage,$columns);
	}
}