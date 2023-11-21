<?php

namespace Core\PermissionGroups;

use Illuminate\Support\ServiceProvider;

class PermissionGroupsServiceProvider extends ServiceProvider{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(){
        $this->loadViewsFrom(__DIR__.'/../views', 'permissionGroups');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(){
        $this->app->bind('permissiongroups', function($app){
            return new PermissionGroups;
        });
    }
}
