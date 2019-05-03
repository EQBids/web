<?php

namespace App\Http\Middleware\admin;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (!Auth::check()){
    		return redirect(route('admin.login'));
	    }
    	if (Auth::user()->hasAnyRol(['superadmin','staff','admin'])){
		    return $next($request);
	    }
        return redirect('/');
    }
}
