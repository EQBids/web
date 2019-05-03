<?php

namespace App\Rules;

use App\Models\Supplier\Supplier;
use Illuminate\Contracts\Validation\Rule;

class supplierInRange implements Rule
{
	protected $lat,$lon,$radius,$country;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lat,$lon,$rad,$country_id)
    {
        $this->lat=$lat;
        $this->lon=$lon;
        $this->radius=$rad;
        $this->country=$country_id;
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
    	return Supplier::query()->where('suppliers.id',$value)->InRange($this->radius,$this->lat,$this->lon,$this->country)->count()>0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Supplier is not in range';
    }
}
