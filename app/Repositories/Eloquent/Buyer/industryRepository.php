<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 7/29/18
 * Time: 2:11 PM
 */

namespace App\Repositories\Eloquent\Buyer;


use App\Models\Buyer\Contractor;
use App\Models\Industry;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Buyer\industriesRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class industryRepository extends BaseRepository implements  industriesRepositoryInterface {

	public function __construct( Industry $model ) {
		parent::__construct( $model );
	}


	public function delete( $value = null, $field = 'id' ) {
		DB::beginTransaction();
		try{
			 Industry::query()->where('parent_id',$value)->update(['parent_id'=>null]);
			 Contractor::query()->where('industry_id',$value)->update(['industry_id'=>null]);
			 parent::delete($value,$field);
			 DB::commit();
			 return true;
		}catch (\Error $error){
			DB::rollBack();
			return false;
		}

	}
}