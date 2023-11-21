<?php

namespace Application\ProductCategory;

use Illuminate\Support\ServiceProvider;

class ProductCategoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'productCategoryViews');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ProductCategory', function($app){
            return new ProductCategory;
        });
    }
}
