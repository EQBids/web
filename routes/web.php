<?php

use App\Http\Middleware\IsLoggedIn;
use Illuminate\Support\Facades\Auth;

Route::post('oauth/pin','Auth\PinController@generatePin');

Route::get('/',['uses'=>'Web\IndexController@index', 'as'=>'home']);

Route::get('login',['uses'=>'Auth\LoginController@show_login','as'=>'show_login']);

Route::get('logout',['uses'=>'Auth\LoginController@logout','as'=>'logout']);

Route::post('login-request-pin',['uses'=>'Auth\LoginController@loginRequestPin','as'=>'loginRequestPin']);

Route::get('enter-pin',['uses'=>'Auth\LoginController@enter_pin','as'=>'login_enter_pin']);

Route::post('enter-pin',['uses'=>'Auth\LoginController@login','as'=>'loginWithPin']);

Route::get('signup-contractor',['uses'=>'Auth\SignupController@signup_contractor','as'=>'signup_contractor']);

Route::get('signup-supplier',['uses'=>'Auth\SignupController@signup_supplier','as'=>'signup_supplier']);

Route::post('signup',['uses'=>'Auth\SignupController@signup','as'=>'signup']);

Route::get('signup-email-sent',['uses'=>'Auth\SignupController@signup_email_sent','as'=>'signup_email_sent']);
Route::get('on-approval',['uses'=>'Auth\SignupController@supplier_on_approval','as'=>'supplier_on_approval']);

Route::get('contact',['uses'=>'Web\IndexController@contact', 'as'=>'contact']);
Route::post('contact-message',['uses'=>'Web\IndexController@process_contact_form','as'=>'contactMessage']);
Route::get('about',['uses'=>'Web\IndexController@about', 'as'=>'about']);


Route::get('design',['uses'=>'Web\IndexController@design', 'as'=>'design']);

Route::get('terms',['uses'=>'Web\IndexController@terms', 'as'=>'terms']);

Route::get('privacy',['uses'=>'Web\IndexController@privacy', 'as'=>'privacy']);

Route::get('design',['uses'=>'Web\IndexController@design', 'as'=>'design']);

Route::get('custom',['uses'=>'Web\IndexController@custom', 'as'=>'custom']);

Route::get('support',['uses'=>'Web\IndexController@support', 'as'=>'support']);

Route::get('perform',['uses'=>'Web\IndexController@perform', 'as'=>'perform']);



Route::middleware([IsLoggedIn::class])->group(function(){

    Route::prefix('contractors')->group(function(){

        Route::get('dashboard',['uses'=>'Web\ContractorController@dashboard','as'=>'contractors_dashboard']);

	    Route::name('contractor.')->group(function (){

            Route::resource('offices','Web\Contractor\OfficesController')->except(['show']);
            
	        Route::get('offices/{office}/delete','Web\Contractor\OfficesController@delete')->name('offices.delete');

            Route::prefix('suppliers')->name('suppliers.')->group(function (){
                Route::get('/','Web\Contractor\SupplierController@index')->name('index');
                Route::get('/{id}/view','Web\Contractor\SupplierController@show')->name('view');
            });

	    	Route::resource('sites','Web\Contractor\siteController');//->except(['store','create']);
	        Route::get('offices/{office}/workers','Web\Contractor\OfficesController@workers')->name('offices.workers');

	        Route::post('offices/{office}/workers/add','Web\Contractor\OfficesController@addWorker')->name('offices.workers.add');

	    	Route::resource('sites','Web\Contractor\siteController');
	    	Route::get('/sites/{id}/delete','Web\Contractor\siteController@delete')->name('sites.delete');

		    Route::get('/orders/process/location','Web\Contractor\orderController@location')->name('orders.process.location');
		    Route::post('/orders/process/location','Web\Contractor\orderController@location_store')->name('orders.process.location.store');
		    Route::get('/orders/process/suppliers','Web\Contractor\orderController@suppliers')->name('orders.process.suppliers');
		    Route::post('/orders/process/suppliers','Web\Contractor\orderController@suppliers_store')->name('orders.process.suppliers.store');
		    Route::get('/orders/process/details','Web\Contractor\orderController@details')->name('orders.process.details');
		    Route::post('/orders/process/details','Web\Contractor\orderController@details_store')->name('orders.process.details.store');
		    Route::get('/orders/process/final','Web\Contractor\orderController@final')->name('orders.process.final');
		    Route::resource('orders','Web\Contractor\orderController')->except(['create']);
		    Route::get('/orders/{order}/delete','Web\Contractor\orderController@delete')->name('orders.delete');
		    Route::get('/orders/{order}/approval','Web\Contractor\orderController@approval')->name('orders.approval');
			Route::post('/orders/{order}/approve','Web\Contractor\orderController@approve')->name('orders.approve');
			Route::post('/orders/uploadContract','Web\Contractor\orderController@uploadContract')->name('orders.uploadContract');
			
		    Route::prefix('/orders/{order}/edit')->name('orders.edit.')->group(function (){
			    Route::post('/begin','Web\Contractor\orderController@edit_begin')->name('begin');
			    Route::post('/cancel','Web\Contractor\orderController@edit_cancel')->name('cancel');
			    Route::get('/site','Web\Contractor\orderController@edit_location')->name('site');
			    Route::put('/site','Web\Contractor\orderController@edit_location_store')->name('site.store');
			    Route::get('/suppliers/available','Web\Contractor\orderController@edit_available_suppliers')->name('suppliers.available');
			    Route::get('/suppliers','Web\Contractor\orderController@edit_suppliers')->name('suppliers');
			    Route::put('/suppliers','Web\Contractor\orderController@edit_suppliers_store')->name('suppliers.store');
			    Route::get('/details','Web\Contractor\orderController@edit_details')->name('details');
			    Route::put('/details','Web\Contractor\orderController@edit_details_store')->name('details.store');
			    Route::get('/final','Web\Contractor\orderController@edit_final')->name('final');

		    });

		    Route::get('/orders/{order}/bids','Web\Contractor\orderController@bids')->name('orders.bids');
		    Route::put('/orders/{order}/bids','Web\Contractor\orderController@save_bid')->name('orders.bid');
		    Route::get('/orders/{order}/close','Web\Contractor\orderController@closing')->name('orders.close');
		    Route::put('/orders/{order}/close','Web\Contractor\orderController@close')->name('orders.close');


		    Route::get('/cart','Web\Contractor\orderController@cart')->name('cart');

		    Route::prefix('equipments')->name('equipment.')->group(function (){

			    Route::get('/','Web\Contractor\EquipmentsController@index')->name('index');
			    Route::get('/category/{slug}','Web\Contractor\EquipmentsController@category')->name('category');

			    Route::get('{id}','Web\Contractor\EquipmentsController@show')->name('show');

		    });

		    Route::middleware([ 'role:contractor-superadmin,contractor-admin'])->group(function() {
			    Route::resource( 'users', 'Web\Contractor\UsersController' )->except( [ 'show' ] );
			    Route::get( 'users/{user}/delete', 'Web\Contractor\UsersController@delete' )->name( 'users.delete' );
		    });



	    });



    });

    Route::prefix('suppliers')->group(function(){

        Route::get('dashboard',['uses'=>'Web\SupplierController@dashboard','as'=>'suppliers_dashboard']);


        Route::name('supplier.')->middleware([
            'role:supplier-superadmin,supplier-admin,supplier-worker,supplier-manager,supplier-salesperson'
        ])->group(function (){


	        Route::prefix('equipments')->name('equipment.')->group(function (){
	        	Route::get('{id}','Web\Supplier\EquipmentsController@show')->name('show');

	        });

	        Route::resource('orders','Web\Supplier\orderController');
	        Route::get('orders/{order}/delete','Web\Supplier\orderController@delete')->name('orders.delete');

	        Route::middleware([ 'role:supplier-superadmin,supplier-admin'])->group(function() {
		        Route::resource( 'users', 'Web\Supplier\UsersController' )->except( [ 'show' ] );
		        Route::get( 'users/{user}/delete', 'Web\Supplier\UsersController@delete' )->name( 'users.delete' );
	        });

	        Route::resource('bids','Web\Supplier\BidsController')->except(['create']);
	        Route::get( 'bids/{order}/create', 'Web\Supplier\BidsController@create' )->name( 'bids.create' );
	        Route::get( 'bids/{bid}/cancel', 'Web\Supplier\BidsController@cancel' )->name( 'bids.cancel' );
	        Route::get( 'bids/{bid}/enable', 'Web\Supplier\BidsController@approval' )->name( 'bids.approval' );
	        Route::post( 'bids/{bid}/enable', 'Web\Supplier\BidsController@approve' )->name( 'bids.approve' );
	        Route::post('bids/{order}/attachments', 'Web\Supplier\BidsController@store_attachment')->name( 'bids.attachments.store');
	        Route::delete('bids/{order}/attachments', 'Web\Supplier\BidsController@destroy_attachment')->name( 'bids.attachments.destroy');
	        Route::get('bids/{bid}/attachments/{attachment}', 'Web\Supplier\BidsController@show_attachment')->name( 'bids.attachments.show');
			Route::get('bids/{bid}/close','Web\Supplier\BidsController@closing')->name( 'bids.close');
	        Route::post('bids/{bid}/close','Web\Supplier\BidsController@close')->name( 'bids.close');

        });

        Route::middleware(['role:supplier-superadmin,supplier-admin'])->name('supplier.')->group(function (){
	        Route::get('inventory','Web\Supplier\InventoryController@index')->name('inventory.index');
	        Route::post('inventory/store','Web\Supplier\InventoryController@store')->name('inventory.store');

	        Route::resource('offices','Web\Supplier\OfficesController')->except(['show']);
	        Route::get('{office}/delete','Web\Supplier\OfficesController@delete')->name('offices.delete');

	        Route::resource('settings','Web\Supplier\SettingsController')->except(['show']);
	        Route::get('settings/{setting}/delete','Web\Supplier\SettingsController@delete')->name('settings.delete');


        });
    });



});
