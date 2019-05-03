<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 *  routes for admin and system only
 *
 */
Route::group(['middleware'=>['system-only','log'],'namespace'=>'Api'],function (){
	Route::apiResource('/user','UserController');

	Route::apiResource('/countries','Geo\CountryController',['only'=>['update']]);
});