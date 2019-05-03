<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class createSubUserRequest extends updateSubUserRequest
{


	public function authorize()
	{
		if(Auth::check()){
			$parent = Auth::user();
			if($parent->is_admin){
				return true;
			}

			if($parent->is_contractor){
				return true;
			}

			if($parent->is_supplier){
				return true;
			}

		}

		return false;
	}
}
