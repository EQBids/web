<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class normalizeJsonMiddleware
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
        $response = $next($request);
		if ($response instanceof JsonResponse) {
			$data = $response->getData();
		}elseif ($response->getStatusCode()>300){ //if isn't a json and is a error, forward it
			return $response;
		}
		else{
			$data = json_decode($response->content());
			$data=$data?$data:$response->content();
			$old_data=$data;
			$data = new \stdClass();
			$data->data = $old_data;
		}
        if (!isset($data->error)){
			$data->error=$response->getStatusCode()>=200 && $response->getStatusCode()<300?0:1;
        }elseif (($data->error!==0 && $data->error!==1)){
	        $data->error_message=$data->error;
			$data->error=$response->getStatusCode()>=200 && $response->getStatusCode()<300?0:1;
        }

        if (!isset($data->error_message) && isset($data->message) && $response->getStatusCode()>300){
	        $data->error_message=$data->message;
	        unset($data->message);
        }
        elseif (!isset($data->error_message) && $data->error){
            $data->error_message='No error message set, contact the administrator';
        }
		$response = new JsonResponse($data,$response->getStatusCode());
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;


        return $response;
    }
}
