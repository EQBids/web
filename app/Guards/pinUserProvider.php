<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 4/12/18
 * Time: 11:11 AM
 */

namespace App\Guards;


use App\Repositories\Eloquent\PinRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class pinUserProvider extends EloquentUserProvider {

	protected $pin_repository;
	public function __construct( HasherContract $hasher, string $model ) {
		parent::__construct( $hasher, $model );
		$this->pin_repository=new PinRepository();
	}

	public function validateCredentials( UserContract $user, array $credentials ) {

		$plain = $credentials['pin'];
		$pin = $this->pin_repository->findOneBy($user->getAuthIdentifier());
		if ($pin && $pin->checkAndInvalidate($plain)){
			//If the pin has expired
			$now = Carbon::now();
			if($now > $pin->expires_at){
				throw ValidationException::withMessages(['pin'=>'The pin has expired']);
			}

			if ($user->status==User::STATUS_BANNED || $user->status==User::STATUS_REJECTED){
				throw ValidationException::withMessages(['pin'=>'Banned or rejected user']);
			}

			if($user->status==User::STATUS_INACTIVE){
				throw ValidationException::withMessages(['pin'=>'Account doesn\'t exists or deleted']);
			}
			if($user->status==User::STATUS_ON_APPROVAL){
				throw ValidationException::withMessages(['pin'=>'Your account has not been approved by the administrator']);
			}

			return true;
		}
		return false;
	}

	public function retrieveByCredentials( array $credentials ) {
		if (empty($credentials) ||
		    (count($credentials) === 1 &&
		     array_key_exists('password', $credentials))) {
			return;
		}

		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value) {
			if (! Str::contains($key, 'pin')) {
				$query->where($key, $value);
			}
		}

		return $query->first();
	}



}