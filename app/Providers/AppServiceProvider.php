<?php

namespace App\Providers;

use App\Models\Product\Equipment;
use App\Observers\EquipmentObserver;
use App\Repositories\Eloquent\PinRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\Repositories\Interfaces\pinRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Equipment::observe(EquipmentObserver::class);
	    Paginator::defaultView('vendor.pagination.bootstrap-4');
	    Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-4');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton(pinRepositoryInterface::class, PinRepository::class);
//        $this->app->bind(usersRepositoryInterface::class, UsersRepository::class);
    }
}
