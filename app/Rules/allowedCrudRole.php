<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class allowedCrudRole implements Rule
{

	protected $role_repository;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->role_repository=app()->make('App\Repositories\Interfaces\roleRepositoryInterface');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Auth::check()){
	        $user = Auth::user();
	        $allowedRoles = $this->role_repository->allowedCrudRoles($user->rols()->first());
	        foreach ($allowedRoles as $role){
		        if ($role->id == $value){
			        return true;
		        }
	        }

        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You are not allowed to use this role');
    }
}
