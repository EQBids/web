<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class subUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    if(Auth::check()){
	    	$parent = Auth::user();
		    $user = $this->route('user');
		    if(!$user){
		    	return false;
		    }
		    if($parent->hasAnyRol(['contractor-superadmin','supplier-superadmin'])){
	    		return $parent->recursiveUsers()->where('id',$user->id)->count()>0;
		    }else{

			    return $user->creator_user_id == Auth::user()->id;
		    }


	    }
	    return false;
	    //       ( Auth::user()->users()->where('id',$this->route('user'))->count()>0);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
