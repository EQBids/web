<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 6/10/18
 * Time: 4:20 PM
 */

namespace App\Repositories\Interfaces\Supplier;


use App\Models\Supplier\Bid;
use App\Repositories\Interfaces\baseRepositoryInterface;
use App\User;

interface bidRepositoryInterface extends baseRepositoryInterface {

	public function accesibleBids(User $user);
	public function approve($value=null,$field='id');
	public function close(Bid $bid);

}