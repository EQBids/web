<?php

namespace App\Http\Requests\Buyer\Order;

use App\Rules\belongOrOwnsSite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class siteOnlyRequest extends locationRequest
{


    public function rules()
    {
        return [
            'site'=>['required','nullable','integer','exists:sites,id',new belongOrOwnsSite(Auth::user()->contractors->first()->id)]
        ];
    }
}
