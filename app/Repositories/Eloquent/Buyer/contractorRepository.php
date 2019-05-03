<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 7:27 PM
 */

namespace App\Repositories\Eloquent\Buyer;


use App\Models\Buyer\Contractor;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class contractorRepository extends BaseRepository implements contractorRepositoryInterface {

	public function __construct() {
		parent::__construct( new Contractor());
	}

}