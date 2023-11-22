<?php
/**
 * PRICE-BOOK MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Sriya <csriyarathne@gmail.com>
 * @copyright 2015 Yasith Samarawickrama
 */
use Illuminate\Support\Facades\Route;
/**
 * PRICE BOOK MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function () {
    Route::group(['prefix' => 'price/type', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'price.type.add', 'uses' => 'PriceBookController@addPriceBookTypeView'
        ]);

        Route::get('json/list', [
            'as' => 'price.type.list', 'uses' => 'PriceBookController@jsonListPriceBookType'
        ]);

        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'price.type.add', 'uses' => 'PriceBookController@addPriceBookType'
        ]);

        Route::post('edit', [
            'as' => 'price.type.edit', 'uses' => 'PriceBookController@editPriceBookType'
        ]);

    });


    Route::group(['prefix' => 'price/standerd', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'price.standerd.add', 'uses' => 'PriceBookController@addStanderdPriceBookView'
        ]);

        Route::get('edit/{id}', [
            'as' => 'price.standerd.edit', 'uses' => 'PriceBookController@editStanderdPriceBookView'
        ]);

        Route::get('json/list', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@jsonListProduct'
        ]);

        Route::get('json/productList', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@jsonListProductDetail'
        ]);

        Route::get('list', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@listStanderdPriceBookView'
        ]);

        Route::get('json/listPriceBook', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@jsonListStanderdPriceBook'
        ]);

        Route::get('json/listPriceBookDetail/{id}', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@jsonListStanderdPriceBookDetail'
        ]);

        Route::get('json/standardProductList', [
            'as' => 'price.standerd.list', 'uses' => 'PriceBookController@jsonProductList'
        ]);

        Route::get('check-mrp', [
            'as' => 'price.standerd.check-mrp', 'uses' => 'PriceBookController@checkMrp'
        ]);

        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'price.standerd.add', 'uses' => 'PriceBookController@addStanderdPriceBook'
        ]);

        Route::post('edit', [
            'as' => 'price.standerd.edit', 'uses' => 'PriceBookController@editStanderdPriceBook'  // Not Implemented Yet
        ]);

    });


    Route::group(['prefix' => 'price/custom', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'price.custom.add', 'uses' => 'PriceBookController@addCustomPriceBookView'
        ]);

        Route::get('json/userList', [
            'as' => 'price.custom.list', 'uses' => 'PriceBookController@jsonListUsers'
        ]);

        Route::get('json/customUserList', [
            'as' => 'price.custom.list', 'uses' => 'PriceBookController@jsonListUsersCustom'
        ]);

        Route::get('list', [
            'as' => 'price.custom.list', 'uses' => 'PriceBookController@listCustomPriceBookView'
        ]);

        Route::get('json/listPriceBook', [
            'as' => 'price.custom.list', 'uses' => 'PriceBookController@jsonListCustomPriceBook'
        ]);

        Route::get('json/listPriceBookDetail/{id}', [
            'as' => 'price.custom.list', 'uses' => 'PriceBookController@jsonListCustomPriceBookDetail'
        ]);

        Route::get('check-mrp', [
            'as' => 'price.custom.check-mrp', 'uses' => 'PriceBookController@checkMrp'
        ]);

        Route::get('edit/{id}', [
            'as' => 'price.custom.edit', 'uses' => 'PriceBookController@editCustomPriceBookView'
        ]);

        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'price.custom.add', 'uses' => 'PriceBookController@addCustomPriceBook'
        ]);

        Route::post('edit', [
            'as' => 'price.custom.edit', 'uses' => 'PriceBookController@editCustomPriceBook'  // Not Implemented Yet
        ]);

    });

    Route::group(['prefix' => 'price/mrp', 'namespace' => 'Core\PriceBook\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'price.mrp.add', 'uses' => 'PriceBookController@addMrpView'
        ]);

        Route::get('json/productList', [
            'as' => 'price.mrp.list', 'uses' => 'PriceBookController@jsonListMrpProductDetail'
        ]);

        Route::get('list', [
            'as' => 'price.mrp.list', 'uses' => 'PriceBookController@listMrpView'
        ]);

        Route::get('excel', [
            'as' => 'price.mrp.excel.add', 'uses' => 'PriceBookController@getExcel'
        ]);

        Route::get('template', [
            'as' => 'price.mrp.excel.add', 'uses' => 'PriceBookController@getTemplate'
        ]);
        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'price.mrp.add', 'uses' => 'PriceBookController@addMrpBook'
        ]);

        Route::post('excel', [
            'as' => 'price.mrp.excel.add', 'uses' => 'PriceBookController@addExcel'
        ]);

    });

});