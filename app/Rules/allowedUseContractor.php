<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class allowedUseContractor implements Rule
{
	protected $user_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id=$user_id;
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


	    $count= DB::table('contractor_user')
	             ->rightJoin('contractors', 'contractor_user.contractor_id', '=', 'contractors.id')
	             ->where([
	             	        ['contractor_user.contractor_id','=',$value],
		                    ['contractor_user.user_id', '=', $this->user_id ]
	                    ])->orWhere('contractors.user_id', '=', $this->user_id)->count();
	    return $count>0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not allowed to use this contractor';
    }
}
