<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/7/18
 * Time: 6:36 PM
 */

namespace App\Repositories\Interfaces\Supplier;


use App\Models\Buyer\Order;
use App\Models\Supplier\Supplier;
use App\Repositories\Interfaces\baseRepositoryInterface;
use App\User;

interface supplierRepositoryInterface extends baseRepositoryInterface {


	public function suppliersInRange($lat,$lon,$area,$country_id,$equipments=null);

	public function supplierUsers($supplier_id,$columns=['*']);

	public function accesibleOrderInvitations( User $user );

	public function rejectOrderInvitations( Supplier $supplier,Order $order );

}