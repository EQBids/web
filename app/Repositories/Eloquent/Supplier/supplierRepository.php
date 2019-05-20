<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/7/18
 * Time: 6:41 PM
 */

namespace App\Repositories\Eloquent\Supplier;


use App\Models\Buyer\Order;
use App\Models\OrderSupplier;
use App\Models\Supplier\Supplier;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Supplier\supplierRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class supplierRepository extends BaseRepository  implements supplierRepositoryInterface {

	public function __construct(Supplier $model ) {
		parent::__construct( $model );
	}

	public function suppliersInRange( $lat, $lon, $area,$country_id,$equipments=null) {
		$q= $this->query->InRange($area,$lat,$lon,$country_id);
		if($equipments){
			$q->whereExists(function ($inventory_query) use($equipments){
				$inner_equipments=$equipments;

				if($inner_equipments instanceof Collection){
					$inner_equipments=$inner_equipments->pluck('id');
				}
				$inventory_query->select(DB::raw(1))
					->from('inventories')->whereRaw('inventories.supplier_id = suppliers.id')
					->whereIn('inventories.equipment_id',$inner_equipments);
			});
		}
		//return $q->orderBy('distance','desc')->get();
		return $q->get();
	}

	public function supplierUsers( $supplier_id,$columns=['*'] ) {
		$users = User::query()->whereExists(function ($exists) use ($supplier_id){
			$exists->select(DB::raw(1))->from('supplier_user')->whereRaw('supplier_user.user_id = users.id');
			if(is_array($supplier_id)){
				$exists->whereIn('supplier_id',$supplier_id);
			}else{
				$exists->where('supplier_id',$supplier_id);
			}
		});
		return $users->get($columns);

	}

	public function accesibleOrderInvitations( User $user ) {
		if(!$user->is_supplier){
			return collect([]);
		}
		$query = OrderSupplier::query();
		$suppliers_id = $user->suppliers()->first()->id;
		$query->where('supplier_id',$suppliers_id);
		$query->distinct('order_id');
		$query->with('order');
		return $query->get();

	}

	public function rejectOrderInvitations( Supplier $supplier, Order $order ) {
		$user = Auth::user();
		if(!$user->is_supplier || !$supplier || !$order){
			return false;
		}
		$orderSupplier = $supplier->orders()->where('order_id',$order->id)->first();
		if($orderSupplier){
			$supplier->orders()->updateExistingPivot($order->id,['status'=>OrderSupplier::STATUS_REJECTED]);

			return true;
		}
		return false;
	}


}