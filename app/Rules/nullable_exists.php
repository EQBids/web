<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class nullable_exists implements Rule
{

	protected $table,$column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table,$column='id')
    {
        $this->table=$table;
        $this->column=$column;
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
    	if(!$this->column){
    		$this->column=$attribute;
	    }
        if($value){
			DB::table($this->table)->where($this->column,$value)->count()>0;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
