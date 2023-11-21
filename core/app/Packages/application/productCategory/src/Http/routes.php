<?php

use Application\ProductCategory\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'productCategory', 'namespace' => 'Application\ProductCategory\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [ProductCategoryController::class, 'addView'])->name('productCategory.add');
        Route::get('list', [ProductCategoryController::class, 'listView'])->name('productCategory.list');
        Route::get('json/list', [ProductCategoryController::class, 'jsonList'])->name('productCategory.jsonList');
        Route::get('edit/{id}', [ProductCategoryController::class, 'editView'])->name('productCategory.edit');
        
        /**
         * POST Routes
         */
        Route::post('add', [ProductCategoryController::class, 'add'])->name('productCategory.store');
        Route::post('status', [ProductCategoryController::class, 'status'])->name('productCategory.status');
        Route::post('edit/{id}', [ProductCategoryController::class, 'edit'])->name('productCategory.update');
    });
});
