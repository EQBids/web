<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        if (Auth::user()->hasAnyRol($roles)){
            return $next($request);
        }


        if($request->isJson()){
            return response()->json([
                'error'=>1,
                'error_message'=>'your credentials are not enough'
            ],401);
        }

        abort(403);
    }
}
