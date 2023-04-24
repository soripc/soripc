<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Guide;
use Modules\Inventory\Models\InventoryTransfer;
use Modules\Inventory\Observers\GuideObserver;
use Modules\Inventory\Observers\InventoryTransferObserver;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerViews();
        Guide::observe(GuideObserver::class);
        InventoryTransfer::observe(InventoryTransferObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(InventoryChangeServiceProvider::class);
        $this->app->register(InventoryKardexServiceProvider::class);
        $this->app->register(InventoryVoidedServiceProvider::class);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/inventory');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/inventory';
        }, \Config::get('view.paths')), [$sourcePath]), 'inventory');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
