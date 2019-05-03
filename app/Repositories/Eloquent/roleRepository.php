<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 7:40 PM
 */

namespace App\Repositories\Eloquent;


use App\Models\Role;
use App\Repositories\Interfaces\roleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class roleRepository extends BaseRepository implements roleRepositoryInterface {

	public function __construct() {
		parent::__construct(new Role());
	}

	public function allowedCrudRoles( $role ) {
		$names=[];
		if ($role instanceof Role){
			$role=$role->name;
		}else{
			$role = strval($role);
		}
		$admin_names=['superadmin','admin','staff'];
		$contractor_names=['contractor-superadmin','contractor-admin','contractor-worker'];
		$supplier_name=['supplier-superadmin','supplier-admin','supplier-manager','supplier-salesperson'];

		/* -- Admins -- */
		$position = array_search($role,$admin_names);
		if ($position!==false){
			$names = array_splice($admin_names,$position);
			$names= array_merge($names,$contractor_names,$supplier_name);
		}elseif($position = array_search($role,$contractor_names) !== false){
			$names = array_splice($contractor_names,$position);
		}elseif($position = array_search($role,$supplier_name) !== false){
			$names = array_splice($supplier_name,$position);
		}
		return Role::whereIn('name',$names)->orderBy('name')->get();

	}

}