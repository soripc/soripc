<?php

namespace Modules\Purchase\Providers;

use App\Models\Tenant\Purchase;
use Illuminate\Support\ServiceProvider;
use Modules\Purchase\Observers\PurchaseObserver;
use Modules\Purchase\Traits\PurchaseTrait;

class PurchaseServiceProvider extends ServiceProvider
{

    use PurchaseTrait;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerViews();
        $this->createCashDocument();
        Purchase::observe(PurchaseObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/purchase');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/purchase';
        }, \Config::get('view.paths')), [$sourcePath]), 'purchase');
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
