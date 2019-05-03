<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/5/18
 * Time: 8:24 AM
 */

namespace App\Repositories\Eloquent;


use App\Models\Security\Pin;
use App\Repositories\Interfaces\baseRepositoryInterface;
use App\Repositories\Interfaces\pinRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PinRepository extends BaseRepository implements pinRepositoryInterface {


	public function __construct() {

		$model = Pin::class;
		parent::__construct(new $model);
	}

	public function findOneBy( $value = null, $field = 'user_id', array $columns = [ '*' ] ) {
		if ($value==null){
			return null;
		}
		return $this->model->query()->firstOrNew(array('user_id'=>$value));
	}


	function generatePin( $user_id, $device_id ) {
		$pin = $this->findOneBy($user_id);
		if ($pin == null){
			return null;
		}
		$_exp_time=DB::table('settings')->where('name','pin_expires_in_secs')->select('value')->first();
		$exp_time=3600;
		if($_exp_time && is_numeric($_exp_time)){
			$exp_time=intval($_exp_time);
		}
		$code=$pin->generatePin($exp_time,$device_id);

		$pin->save();
		return $code;

	}

}