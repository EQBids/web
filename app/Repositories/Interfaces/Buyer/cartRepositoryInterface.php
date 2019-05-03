<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/29/18
 * Time: 4:12 PM
 */

namespace App\Repositories\Interfaces\Buyer;


use App\Repositories\Interfaces\baseRepositoryInterface;

interface cartRepositoryInterface extends baseRepositoryInterface {


	function flushCart($user_id);

	/**
	 * add an equipment to the shoping cart
	 * @param $id
	 *
	 * @return mixed return 0 if the equipment status is 0 or doesn't exists. -1 if the item is already in the cart, $id if the item was added succesfuly
	 */
	function addEquipment($user_id,$id);

	function removeEquipment($user_id,$id);

	function getProductList($user_id);

	function updateEquipmentDetails($cart_id,$values);
}