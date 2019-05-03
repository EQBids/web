<?php

namespace App\Providers;

use App\Repositories\Eloquent\Buyer\billingRepository;
use App\Repositories\Eloquent\Buyer\contractorRepository;
use App\Repositories\Eloquent\Buyer\OfficeRepository as contractorOfficeRepository;
use App\Repositories\Eloquent\Buyer\orderRepository;
use App\Repositories\Eloquent\Buyer\siteRepository;
use App\Repositories\Eloquent\Product\BrandRepository;
use App\Repositories\Eloquent\Product\CategoryRepository;
use App\Repositories\Eloquent\Geo\cityRepository;
use App\Repositories\Eloquent\Geo\CountryRepository;
use App\Repositories\Eloquent\Geo\metroRepository;
use App\Repositories\Eloquent\Geo\stateRepository;
use App\Repositories\Eloquent\PinRepository;
use App\Repositories\Eloquent\Product\EquipmentRepository;
use App\Repositories\Eloquent\Product\InventoryRepository;
use App\Repositories\Eloquent\Product\OfficeRepository as supplierOfficeRepository;
use App\Repositories\Eloquent\roleRepository;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\Repositories\Eloquent\Supplier\SettingsRepository as SupplierSettingsRepository;
use App\Repositories\geocodingRepository;
use App\Repositories\Interfaces\Buyer\billingRepositoryInterface;
use App\Repositories\Interfaces\Buyer\cartRepositoryInterface;
use App\Repositories\Interfaces\Buyer\contractorRepositoryInterface;
use App\Repositories\Interfaces\Buyer\officeRepositoryInterface as contractorOfficeRepositoryInterface;
use App\Repositories\Interfaces\Buyer\orderRepositoryInterface;
use App\Repositories\Interfaces\Buyer\siteRepositoryInterface;
use App\Repositories\Interfaces\Product\brandRepositoryInterface;
use App\Repositories\Interfaces\Product\categoryRepositoryInterface;
use App\Repositories\Interfaces\Geo\cityRepositoryInterface;
use App\Repositories\Interfaces\Geo\CountryRepositoryInterface;
use App\Repositories\Interfaces\Geo\metroRepositoryInterface;
use App\Repositories\Interfaces\Geo\stateRepositoryInterface;
use App\Repositories\Interfaces\geocodingRepositoryInterface;
use App\Repositories\Interfaces\pinRepositoryInterface;
use App\Repositories\Interfaces\Product\equipmentRepositoryInterface;
use App\Repositories\Interfaces\Product\inventoryRepositoryInterface;
use App\Repositories\Interfaces\Product\officeRepositoryInterface as supplierOfficeRepositoryInterface;
use App\Repositories\Interfaces\roleRepositoryInterface;
use App\Repositories\Interfaces\settingsRepositoryInterface;
use App\Repositories\Interfaces\usersRepositoryInterface;
use App\Repositories\Interfaces\Supplier\settingsRepositoryInterface as supplierSettingsRepositoryInterface;
use App\Repositories\Eloquent\Buyer\cartRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(usersRepositoryInterface::class,UsersRepository::class);
        $this->app->bind(pinRepositoryInterface::class,PinRepository::class);
        $this->app->bind(CountryRepositoryInterface::class,CountryRepository::class);
	    $this->app->bind(stateRepositoryInterface::class,stateRepository::class);
	    $this->app->bind(metroRepositoryInterface::class,metroRepository::class);
	    $this->app->bind(cityRepositoryInterface::class,cityRepository::class);
	    $this->app->bind(contractorRepositoryInterface::class,contractorRepository::class);
	    $this->app->bind(roleRepositoryInterface::class,roleRepository::class);
	    $this->app->bind(siteRepositoryInterface::class,siteRepository::class);
	    $this->app->bind(geocodingRepositoryInterface::class,geocodingRepository::class);
	    $this->app->bind(billingRepositoryInterface::class,billingRepository::class);
	    $this->app->bind(categoryRepositoryInterface::class,CategoryRepository::class);
	    $this->app->bind(equipmentRepositoryInterface::class,EquipmentRepository::class);
        $this->app->bind(inventoryRepositoryInterface::class,InventoryRepository::class);
        $this->app->bind(orderRepositoryInterface::class,orderRepository::class);
        $this->app->bind(brandRepositoryInterface::class,BrandRepository::class);
        $this->app->bind(settingsRepositoryInterface::class,SettingsRepository::class);
        $this->app->bind(cartRepositoryInterface::class,cartRepository::class);
        $this->app->bind(contractorOfficeRepositoryInterface::class,contractorOfficeRepository::class);
        $this->app->bind(supplierOfficeRepositoryInterface::class,supplierOfficeRepository::class);
        $this->app->bind(supplierSettingsRepositoryInterface::class,SupplierSettingsRepository::class);
    }
}
