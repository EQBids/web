<?php
/**
 * Created by PhpStorm.
 * User: smith
 * Date: 3/2/18
 * Time: 11:20 AM
 */

//admin routes should be here

Route::name('admin.')->middleware('admin.authenticated')->group(function(){

	Route::get('/',['uses'=>'Web\Admin\DashboardController@index','as'=>'dashboard']);

	Route::resource('/users','Web\Admin\UserController')->except(['show']);
	Route::get('/users/{id}/delete','Web\Admin\UserController@delete')->name('users.delete');

	Route::resource('/sites','Web\Admin\Contractor\SitesController');
	Route::get('/sites/{site}/delete','Web\Admin\Contractor\SitesController@delete')->name('sites.delete');

	Route::get('/categories','Web\Admin\CategoriesController@index')->name('categories.index');

    Route::get('/categories/{id}/edit','Web\Admin\CategoriesController@edit')->name('categories.edit');

    Route::post('/categories/{id}/update','Web\Admin\CategoriesController@update')->name('categories.update');

    Route::get('/categories/create','Web\Admin\CategoriesController@create')->name('categories.create');

    Route::post('/categories/store','Web\Admin\CategoriesController@store')->name('categories.store');

    Route::get('/categories/{id}/delete','Web\Admin\CategoriesController@delete')->name('categories.delete');


    Route::prefix('equipment')->name('equipment.')->group(function (){

        Route::get('/','Web\Admin\EquipmentsController@index')->name('index');

        Route::get('/create','Web\Admin\EquipmentsController@create')->name('create');

        Route::post('/store','Web\Admin\EquipmentsController@store')->name('store');

        Route::get('/{id}/edit','Web\Admin\EquipmentsController@edit')->name('edit');

        Route::get('/{id}/delete','Web\Admin\EquipmentsController@delete')->name('delete');

        Route::delete('{id}/destroy','Web\Admin\EquipmentsController@destroy')->name('destroy');

        Route::put('/{id}/update','Web\Admin\EquipmentsController@update')->name('update');


	    Route::get('/listing','Web\Admin\EquipmentsController@equipment_listing_index')->name('listing.index');
	    Route::get('/listing/category/{slug}','Web\Admin\EquipmentsController@equipment_listing_category')->name('listing.category');

	    Route::get('/listing/{id}','Web\Admin\EquipmentsController@equipment_listing_show')->name('listing.show');


    });

    Route::resource('settings','Web\Admin\SettingsController')->except(['show']);

    Route::get('/settings/{setting}/delete','Web\Admin\SettingsController@delete')->name('settings.delete');

    Route::prefix('applicants')->name('applicants.')->group(function(){

        Route::get('/','Web\Admin\ApplicantsController@index')->name('index');

        Route::get('view/{applicant}','Web\Admin\ApplicantsController@view')->name('view');

        Route::post('{applicant}/accept','Web\Admin\ApplicantsController@accept')->name('accept');

        Route::post('{applicant}/reject','Web\Admin\ApplicantsController@reject')->name('reject');
	    Route::post('{applicant}/destroy','Web\Admin\ApplicantsController@destroy')->name('destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function (){
    	Route::get('contractors','Web\Admin\ReportsController@contractors')->name('contractors');
	    Route::get('equipments','Web\Admin\ReportsController@equipments')->name('equipments');
	    Route::get('suppliers','Web\Admin\ReportsController@suppliers')->name('suppliers');
    });

	Route::get('/logout','Web\Admin\LoginController@logout')->name('logout');

	Route::resource('industries','Web\Admin\IndustryController')->except(['show']);
	Route::get('/industries/{industry}/delete','Web\Admin\IndustryController@delete')->name('industries.delete');

	Route::resource('materials','Web\Admin\MaterialsController')->except(['show']);
	Route::get('/materials/{material}/delete','Web\Admin\MaterialsController@delete')->name('materials.delete');

	Route::get('/orders','Web\Admin\OrdersController@index')->name('orders.index');
	Route::get('/orders/{order}','Web\Admin\OrdersController@show')->name('orders.show');
	Route::get('/orders/{order}/delete','Web\Admin\OrdersController@delete')->name('orders.delete');
	Route::delete('/orders/{order}','Web\Admin\OrdersController@destroy')->name('orders.destroy');

});

Route::get('/login','Web\Admin\LoginController@showLoginForm')->name('admin.login_form');
Route::post('/login','Web\Admin\LoginController@login')->name('admin.login');

