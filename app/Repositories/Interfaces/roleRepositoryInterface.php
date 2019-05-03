<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/10/18
 * Time: 7:42 PM
 */

namespace App\Repositories\Interfaces;


interface roleRepositoryInterface extends baseRepositoryInterface {

	/**
	 * Return a list containing the roles that a user  belonging to $role can do CRUD
	 * @param $role the role of the user that's doing crud
	 *
	 * @return mixed the list of allowed Roles
	 */

	public function allowedCrudRoles($role);

}