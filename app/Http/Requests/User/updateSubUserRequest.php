<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use App\Rules\allowedUseContractor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\allowedCrudRole;
use Illuminate\Validation\Rule;
use App\Rules\belongOrOwnsSite;

class updateSubUserRequest extends userCDRequest
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
		    if($parent->is_admin){
		    	return true;
		    }
		    if($parent->hasAnyRol(['contractor-superadmin','supplier-superadmin'])){
			    return $parent->recursiveUsers()->where('id',$user->id)->count()>0;
		    }else{

			    return $user->creator_user_id == Auth::user()->id;
		    }


	    }

	    return false;
    }

    public function rules() {
    	$user = $this->route('user');
	    //if user is not set this should fail
	    $rules= [
		    'role'=>['filled','integer',new allowedCrudRole],
		    'first_name'=>'filled|string|max:100',
		    'last_name'=>'filled|string|max:100',
		    'email'=>['filled','string','email','max:100',Rule::unique('users','email')->ignore($user?$user->id:-1)],

	    ];
	    $rules['site_id']=['integer'];
	    if(Auth::user()->is_contractor){
	        array_push($rules['site_id'],new belongOrOwnsSite(Auth::user()->contractors->first()->id));
		    $rules['office_id']=['integer',new allowedUseContractor(Auth::user()->id)];
	    }elseif(Auth::user()->is_supplier){
	    	$rules['office_id']=['integer',Rule::exists('supplier_user','supplier_id')->where('user_id',Auth::user()->id)];
	    }

	    $rules['contractor_id']=['integer',Rule::exists('contractors','id')->where('user_id',Auth::user()->id)];
	    $desiredRol = Role::find($this->get('role'));

	    if($desiredRol && $desiredRol->name=='contractor-admin' && Auth::user()->is_admin){
		    $rules['contractor_id']= array_prepend($rules['contractor_id'],'required');
	    }

	    return $rules;
    }

}
