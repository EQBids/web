<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/26/18
 * Time: 5:32 PM
 */

namespace App\Repositories\Interfaces\Buyer;


use App\Models\Buyer\Cart;
use App\Models\Buyer\Order;
use App\Repositories\Interfaces\baseRepositoryInterface;
use App\User;

interface orderRepositoryInterface extends baseRepositoryInterface {


	public function createFromCart(Cart $cart);

	public function accesibleOrders(User $user);

	public function beginEditProcess(Order $order);

	public function getEditingSite(Order $order);

	public function setEditingSite(Order $order,$site_id);

	public function getEditingSuppliers(Order $order);

	public function setEditingSuppliers(Order $order,$suppliers);

	public function getEditingItems(Order $order);

	public function setEditingItems(Order $order,$items);

	public function finishEditing(Order $order);

	public function updateBids(Order $order,$bids);

	public function close(Order $order);

}