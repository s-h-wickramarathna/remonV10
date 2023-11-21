<?php

use Application\SalesOrderManage\Http\Controllers\SalesOrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'sales-order', 'namespace' => 'Application\SalesOrderManage\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('create', [SalesOrderController::class, 'createView'])->name('sales.order.create');
        Route::get('index', [SalesOrderController::class, 'indexView'])->name('sales.order.index');
        Route::get('get/{id}', [SalesOrderController::class, 'getOrderData'])->name('sales.order.detail');
        Route::get('discard/{id}', [SalesOrderController::class, 'discardOrder'])->name('sales.order.discard');
        Route::get('json/list', [SalesOrderController::class, 'getOrderList'])->name('sales.order.jsonList');
        Route::get('edit/{id}', [SalesOrderController::class, 'editView'])->name('sales.order.edit');
        Route::get('getProductByBrand', [SalesOrderController::class, 'getProductByBrand'])->name('sales.order.getProductByBrand');
        Route::get('getProductByCategory', [SalesOrderController::class, 'getProductByCategory'])->name('sales.order.getProductByCategory');
        Route::get('toPrint', [SalesOrderController::class, 'toPrint'])->name('sales.order.toPrint');
        Route::get('getData', [SalesOrderController::class, 'getOrderDetail'])->name('sales.order.getData');
        Route::get('testReturn', [SalesOrderController::class, 'testReturnTo'])->name('sales.order.testReturn');

        /**
         * POST Routes
         */
        Route::post('add', [SalesOrderController::class, 'add'])->name('sales.order.add');
        Route::post('getFreeIssue', [SalesOrderController::class, 'getFreeIssue'])->name('sales.order.getFreeIssue');
        Route::post('getDiscount', [SalesOrderController::class, 'getDiscount'])->name('sales.order.getDiscount');
    });
});
