<?php

namespace Core\PriceBook;

use Illuminate\Support\ServiceProvider;

class PriceBookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'priceBook');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('pricebook', function($app){
            return new PriceBook;
        });
    }
}
