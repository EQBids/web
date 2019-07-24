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
 *  routes for admins only
 *
 */




Route::group(['middleware'=>['auth:api','log'],'namespace'=>'Api'],function (){
	Route::apiResource('/user','UserController');

	//geo routes
	Route::apiResource('/countries','Geo\CountryController',['only'=>['update']]);
	Route::apiResource('/metros','Geo\MetroController',['only'=>['update']]);
	Route::apiResource('/states','Geo\StateController',['only'=>['update']]);
	Route::apiResource('/cities','Geo\CityController',['only'=>['update']]);

	Route::apiResource('/sites','Buyer\SiteController',['only'=>['store','update','destroy']]);
	Route::get('/sites/resume/{contractor?}','Buyer\SiteController@resume');

	Route::name('contractor.')->prefix('contractors')->group(function (){
		//Route::apiResource('/{contractor}/billings','Buyer\BillingController');
	});



	//equipment


	//Route::post('/cart','Buyer\CartController@store');
	//Route::group(['middleware' => 'guest'], function () {
	//	Route::get('/cart', 'Buyer\CartController@store');
	//});
	Route::apiResource('/cart','Buyer\CartController',['exception'=>['index']]);
	
	

	Route::prefix('/orders/process')->name('orders.process.')->group(function (){

		Route::get('/site','Buyer\OrderController@location')->name('site');
		Route::post('/site','Buyer\OrderController@location_store')->name('site.store');
		Route::get('/suppliers/available','Buyer\OrderController@available_suppliers')->name('suppliers.available');
		Route::get('/suppliers','Buyer\OrderController@suppliers')->name('suppliers');
		Route::post('/suppliers','Buyer\OrderController@suppliers_store')->name('suppliers.store');
		Route::get('/details','Buyer\OrderController@details')->name('details');
		Route::post('/details','Buyer\OrderController@details_store')->name('details.store');
	});
	Route::apiResource('/orders','Buyer\OrderController')->except(['destroy']);
	Route::get('/orders/{order}/equipments','Buyer\OrderController@equipments');
	Route::get('/orders/{order}/suppliers','Buyer\OrderController@order_suppliers');
	Route::post('/orders/{order}/approve','Buyer\OrderController@approve');
	Route::post('/orders/{order}/cancel','Buyer\OrderController@cancel');
	Route::post('/orders/{order}/close','Buyer\OrderController@close');


	//order edit
	Route::prefix('/orders/{order}/edit')->name('orders.edit.')->group(function (){
		Route::post('/begin','Buyer\OrderController@edit_begin')->name('begin');
		Route::post('/cancel','Buyer\OrderController@edit_cancel')->name('cancel');
		Route::get('/site','Buyer\OrderController@edit_location')->name('site');
		Route::put('/site','Buyer\OrderController@edit_location_store')->name('site.store');
		Route::get('/suppliers/available','Buyer\OrderController@edit_available_suppliers')->name('suppliers.available');
		Route::get('/suppliers','Buyer\OrderController@edit_suppliers')->name('suppliers');
		Route::put('/suppliers','Buyer\OrderController@edit_suppliers_store')->name('suppliers.store');
		Route::get('/details','Buyer\OrderController@edit_details')->name('details');


	});

	Route::get('/orders/{order}/bids','Buyer\OrderController@bids')->name('orders.bids');
	Route::put('/orders/{order}/bids','Buyer\OrderController@save_bid')->name('orders.bid');
	Route::put('/orders/{order}/close','Buyer\orderController@close')->name('orders.close');


	/** ----- GENERAL ---- */
	Route::get('/users/roles','UserController@allowedRoles')->name('users.roles');
	Route::apiResource('/users','UserController');

	Route::get('/profile','UserController@self')->name('profile.show');
	Route::put('/profile','UserController@selfUpdate')->name('profile.update');

	Route::middleware([
		'role:supplier-superadmin,supplier-admin,supplier-worker,supplier-manager,supplier-salesperson'
	])->group(function (){
		Route::apiResource('bids','Supplier\BidController');
	});
});



Route::group(['namespace'=>'Api'],function (){

	//Route::apiResource('/requestPin','Auth\LoginController');
	Route::get('/requestPin','Auth\LoginController@requestPin');
	Route::post('/login','Auth\LoginController@login');

	Route::apiResource('/countries','Geo\CountryController',['only'=>['index','show']]);
	Route::apiResource('/metros','Geo\MetroController',['only'=>['index','show']]);
	Route::apiResource('/states','Geo\StateController',['only'=>['index','show']]);
	Route::apiResource('/cities','Geo\CityController',['only'=>['index','show']]);

	Route::post('/contractor/signup','UserController@contractorSignup');

	Route::apiResource('/sites','Buyer\SiteController',['only'=>['index','show']]);

	Route::apiResource('/categories','Product\CategoryController',['only'=>['index','show']]);
	Route::get('/categories/{category}/items','Product\CategoryController@items')->name('categories.items');

	Route::post('/cart','Buyer\CartController@store')->name('cart.store');
	Route::delete('/cart/{cart}','Buyer\CartController@destroy')->name('cart.destroy');
	Route::delete('/cart','Buyer\CartController@flush')->name('cart.flush');
	Route::apiResource('/equipments','Product\EquipmentController',['only'=>['index','show']]);
	//Route::apiResource('/cart','Buyer\CartController',['only'=>['store']]);
	
	
});
