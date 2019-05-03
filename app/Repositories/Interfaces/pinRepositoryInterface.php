<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/5/18
 * Time: 8:45 AM
 */


namespace App\Repositories\Interfaces;

interface pinRepositoryInterface extends \App\Repositories\Interfaces\baseRepositoryInterface{


	/**
	 * Generates a new pin for $user_id. if $user_id already has a pin, the old one is being replaced.
	 * if $user_id doesn't exists null is returned.
	 * @param $user_id the id of the user who's going to receive a new pin or refresh the old one.
	 *
	 * @return mixed if the pin is successfully generated, it's value is returned as string. otherwise null is returned.
	 */
	function generatePin($user_id, $device_id);

}