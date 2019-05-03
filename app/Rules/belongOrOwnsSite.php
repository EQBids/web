<?php

namespace App\Rules;

use App\Models\Buyer\Site;
use Illuminate\Contracts\Validation\Rule;

class belongOrOwnsSite implements Rule
{

	protected $contractor_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($contractor_id)
    {
        $this->contractor_id=$contractor_id;
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
        return Site::query()->where('contractor_id',$this->contractor_id)->where('id',$value)->count()>0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot use this site';
    }
}
