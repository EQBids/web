<?php

namespace App\Http\Middleware;

use Closure;

class systemOnly
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
    	$secure_hosts=config('eqbids.system_allowed_hosts');
    	$secure_hosts=explode('|',$secure_hosts);
    	foreach ($secure_hosts as $host){
    		if (preg_match('/'.$host.'/',$request->ip())){
			    return $next($request);
		    }
	    }
	    return response()->json(['message'=>'ip not allowed to operate as system'],401);

    }
}
