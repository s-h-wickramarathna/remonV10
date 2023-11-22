<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function () {
    Route::group(['prefix' => 'customer', 'namespace' => 'Application\CustomerManage\Http\Controllers'], function () {
        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'customer.add', 'uses' => 'CustomerController@addView'
        ]);

        Route::get('list', [
            'as' => 'customer.list', 'uses' => 'CustomerController@listView'
        ]);

        Route::get('json/list', [
            'as' => 'customer.list', 'uses' => 'CustomerController@jsonList'
        ]);

        Route::get('edit/{id}', [
            'as' => 'customer.edit', 'uses' => 'CustomerController@editView'
        ]);

        Route::get('report/list', [
            'as' => 'report.customer.list', 'uses' => 'CustomerController@reportView'
        ]);

        Route::get('list/download', [
            'as' => 'report.customer.list', 'uses' => 'CustomerController@customerReportDownload'
        ]);


        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'customer.add', 'uses' => 'CustomerController@add'
        ]);

        Route::post('delete', [
            'as' => 'vehicle.delete', 'uses' => 'VehicleController@delete'
        ]);

        Route::post('edit/{id}', [
            'as' => 'customer.edit', 'uses' => 'CustomerController@edit'
        ]);

        Route::post('status', [
            'as' => 'customer.status', 'uses' => 'CustomerController@status'
        ]);

        Route::post('credit-status', [
            'as' => 'customer.credit_status', 'uses' => 'CustomerController@creditLimitStatus'
        ]);

    });


});