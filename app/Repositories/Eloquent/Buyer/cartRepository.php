<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/29/18
 * Time: 3:47 PM
 */

namespace App\Repositories\Eloquent\Buyer;
use App\Models\Buyer\Cart;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;

use App\Repositories\Interfaces\Buyer\cartRepositoryInterface;

class cartRepository extends BaseRepository implements cartRepositoryInterface {

	protected $entry_name = "cart";
	protected $equipment_repository;
	public function __construct(Cart $cart,equipmentRepositoryInterface $equipment_repository) {
		parent::__construct($cart);
		$this->equipment_repository=$equipment_repository;
	}


	function addEquipment($user_id, $id ) {
		$cart=$this->findOneBy($user_id,'user_id');
		$id = intval($id);
		if(!$this->equipment_repository->active()->findOneBy($id)){
			return 0;
		}
		$alreadyExists = $cart->items->where('id',$id)->first();
		if($alreadyExists){
			return -1;
		}
		$cart->items()->attach($id);
		return $id;
	}

	function flushCart($user_id) {
		$cart=$this->findOneBy($user_id,'user_id');
		$cart->items()->sync([]);
		$cart->save();
	}

	function removeEquipment($user_id, $id ) {
		$cart=$this->findOneBy($user_id,'user_id');
		$id = intval($id);
		$cart->items()->detach($id);
		$cart->save();
		return $id;
	}

	function getProductList($user_id) {
		return $this->findOneBy($user_id,'user_id')->items;
	}

	public function findOneBy( $value = null, $field = 'id', array $columns = [ '*' ] ) {
		return $this->query->where($field,$value)->select($columns)->firstOrCreate([$field => $value]);
	}

	function updateEquipmentDetails( $cart_id, $values ) {
		$cart =$this->findOneBy($cart_id);
		foreach ($values as &$value){
			$value['extras']=[];
			if(isset($value['notes'])){
				$value['extras']['notes']=$value['notes'];
			}
		}
		$items = $cart->items()->sync($values);

	}

}