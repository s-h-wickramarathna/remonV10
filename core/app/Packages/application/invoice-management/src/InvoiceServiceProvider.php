<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 12/10/2015
 * Time: 9:37 AM
 */

namespace Application\InvoiceManage;

use Illuminate\Support\ServiceProvider;


class InvoiceServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'invoiceManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

}