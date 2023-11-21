<?php

/**
 * PRICE BOOK MIDDLEWARE
 */
use Core\PriceBook\Http\Controllers\PriceBookController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'price/type', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [PriceBookController::class, 'addPriceBookTypeView'])->name('price.type.add');
        Route::get('json/list', [PriceBookController::class, 'jsonListPriceBookType'])->name('price.type.list');

        /**
         * POST Routes
         */
        Route::post('add', [PriceBookController::class, 'addPriceBookType'])->name('price.type.store');
        Route::post('edit', [PriceBookController::class, 'editPriceBookType'])->name('price.type.edit');
    });


    Route::group(['prefix' => 'price/standerd', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [PriceBookController::class, 'addStanderdPriceBookView'])->name('price.standerd.add');
        Route::get('edit/{id}', [PriceBookController::class, 'editStanderdPriceBookView'])->name('price.standerd.edit');
        Route::get('json/list', [PriceBookController::class, 'jsonListProduct'])->name('price.standerd.list');
        Route::get('json/productList', [PriceBookController::class, 'jsonListProductDetail'])->name('price.standerd.listProduct');
        Route::get('list', [PriceBookController::class, 'listStanderdPriceBookView'])->name('price.standerd.list');
        Route::get('json/listPriceBook', [PriceBookController::class, 'jsonListStanderdPriceBook'])->name('price.standerd.listPriceBook');
        Route::get('json/listPriceBookDetail/{id}', [PriceBookController::class, 'jsonListStanderdPriceBookDetail'])->name('price.standerd.listPriceBookDetail');
        Route::get('json/standardProductList', [PriceBookController::class, 'jsonProductList'])->name('price.standerd.listProductList');
        Route::get('check-mrp', [PriceBookController::class, 'checkMrp'])->name('price.standerd.check-mrp');

        /**
         * POST Routes
         */
        Route::post('add', [PriceBookController::class, 'addStanderdPriceBook'])->name('price.standerd.store');
        Route::post('edit', [PriceBookController::class, 'editStanderdPriceBook'])->name('price.standerd.update');
    });


    Route::group(['prefix' => 'price/custom', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [PriceBookController::class, 'addCustomPriceBookView'])->name('price.custom.add');
        Route::get('json/userList', [PriceBookController::class, 'jsonListUsers'])->name('price.custom.list');
        Route::get('json/customUserList', [PriceBookController::class, 'jsonListUsersCustom'])->name('price.custom.listCustomUser');
        Route::get('list', [PriceBookController::class, 'listCustomPriceBookView'])->name('price.custom.list');
        Route::get('json/listPriceBook', [PriceBookController::class, 'jsonListCustomPriceBook'])->name('price.custom.listPriceBook');
        Route::get('json/listPriceBookDetail/{id}', [PriceBookController::class, 'jsonListCustomPriceBookDetail'])->name('price.custom.listPriceBookDetail');
        Route::get('check-mrp', [PriceBookController::class, 'checkMrp'])->name('price.custom.check-mrp');
        Route::get('edit/{id}', [PriceBookController::class, 'editCustomPriceBookView'])->name('price.custom.edit');

        /**
         * POST Routes
         */
        Route::post('add', [PriceBookController::class, 'addCustomPriceBook'])->name('price.custom.store');
        Route::post('edit', [PriceBookController::class, 'editCustomPriceBook'])->name('price.custom.update');
    });

    Route::group(['prefix' => 'price/mrp', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [PriceBookController::class, 'addMrpView'])->name('price.mrp.add');
        Route::get('json/productList', [PriceBookController::class, 'jsonListMrpProductDetail'])->name('price.mrp.listProduct');
        Route::get('list', [PriceBookController::class, 'listMrpView'])->name('price.mrp.list');
        Route::get('excel', [PriceBookController::class, 'getExcel'])->name('price.mrp.excel.add');
        Route::get('template', [PriceBookController::class, 'getTemplate'])->name('price.mrp.excel.template');

        /**
         * POST Routes
         */
        Route::post('add', [PriceBookController::class, 'addMrpBook'])->name('price.mrp.store');
        Route::post('excel', [PriceBookController::class, 'addExcel'])->name('price.mrp.excel.store');
    });

});
