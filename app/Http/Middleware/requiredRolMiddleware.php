<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class requiredRolMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,... $rols)
    {
    	if (Auth::check() && Auth::user()->hasAnyRol($rols)){
		    return $next($request);
	    }

	    return response()->json([
	    	'error'=>1,
		    'error_message'=>'you credentials are not enough'
	    ],401);

    }
}
