<?php
namespace App\Repositories\Interfaces;

use App\User;

interface usersRepositoryInterface extends baseRepositoryInterface {

    /**
     * Checks if an email belongs to some user in the DB.
     * @param $email
     * @return mixed
     */
    public function emailExists($email);

    /**
     * Creates an User and assigns him a role.
     * @param $data
     * @param $role
     * @return mixed
     */
    public function createWithRole($data,$role);

    /**
     * Changes the status of an user (in its corresponding table).
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function changeStatus($user_id,$status);

	/**
	 * Updates an User and assigns him a role if needed.
	 * @param $id
	 * @param $data
	 * @param $role
	 * @return mixed
	 */
	public function updateWithRole($id,$data,$role);

    /**
     * Finds all the users that have status = 0
     * @return mixed
     */
	public function findApplicants();

    /**
     * Finds an applicant by an specific field.
     * @param $value
     * @param string $field
     * @return mixed
     */
	public function findApplicantBy($value,$field = 'id');

	public function acceptApplicant($data,$id);

	public function rejectApplicant($id);

	/**
	 *  adds a filter to return only users created by the specified user
	 *  @param  $parent_id id of the user that created the requested users.
	 *  @return $this
	 */
	public function createdBy($parent_id);


	/**
	 *  adds a filter to return only users created by the specified user or it's childs
	 *  @param  $parent_id id of the user that created the requested users.
	 *  @return $this
	 */
	public function allChilds($parent_id);


	public function accesibleUsers(User $user);

}