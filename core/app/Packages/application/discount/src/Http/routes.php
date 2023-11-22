<?php
/**
 * PERMISSIONS MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Yasith Samarawickrama <yazith11@gmail.com>
 * @copyright 2015 Yasith Samarawickrama
 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function () {
    Route::group(['prefix' => 'discount/group', 'namespace' => 'Application\Discount\Http\Controllers'], function () {
        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'discountGroup.add', 'uses' => 'DiscountController@addGroupView'
        ]);

        Route::get('json/getProducts', [
            'as' => 'discountGroup.json', 'uses' => 'DiscountController@getProducts'
        ]);

        Route::get('list', [
            'as' => 'discountGroup.list', 'uses' => 'DiscountController@listViewDiscountGroup'
        ]);

        Route::get('json/list', [
            'as' => 'discountGroup.list', 'uses' => 'DiscountController@jsonListGroupView'
        ]);

        Route::get('json/listDetailGroup', [
            'as' => 'discountGroup.list', 'uses' => 'DiscountController@jsonListGroupDetailView'
        ]);

        Route::get('json/getRuleType', [
            'as' => 'discountGroup.json', 'uses' => 'DiscountController@getRuleType'
        ]);
        /**
         * POST Routes
         */
        Route::post('add', [
            'as' => 'discountGroup.add', 'uses' => 'DiscountController@addGroup'
        ]);

        Route::post('status', [
            'as' => 'discountGroup.status', 'uses' => 'DiscountController@statusGroup'
        ]);



    });

    Route::group(['prefix' => 'discount/rule', 'namespace' => 'Application\Discount\Http\Controllers'], function () {
        /*
         * Get Routes
         */
        Route::get('add', [
            'as' => 'discountRule.add', 'uses' => 'DiscountController@addRuleView'
        ]);

        Route::get('json/listDetailRule', [
            'as' => 'discountRule.list', 'uses' => 'DiscountController@jsonListRuleDetailView'
        ]);

        Route::get('list', [
            'as' => 'discountRule.list', 'uses' => 'DiscountController@listViewDiscountRule'
        ]);

        Route::get('json/list', [
            'as' => 'discountRule.list', 'uses' => 'DiscountController@jsonListRuleView'
        ]);
        /*
         * Post Routes
         */

        Route::post('add', [
            'as' => 'discountRule.add', 'uses' => 'DiscountController@addRule'
        ]);

        Route::post('status', [
            'as' => 'discountRule.status', 'uses' => 'DiscountController@statusRule'
        ]);
    });

    Route::group(['prefix' => 'discount/outletAssign', 'namespace' => 'Application\Discount\Http\Controllers'], function () {
        /*
         * Get Routes
         */
        Route::get('add', [
            'as' => 'discounTotOutlet.add', 'uses' => 'DiscountController@addGroupOutletView'
        ]);

        Route::get('json/getLocation', [
            'as' => 'discounTotOutlet.json', 'uses' => 'DiscountController@getLocation'
        ]);

        Route::get('list', [
            'as' => 'discounTotOutlet.list', 'uses' => 'DiscountController@listGroupOutletView'
        ]);

        Route::get('json/list', [
            'as' => 'discounTotOutlet.list', 'uses' => 'DiscountController@listGroupOutlet'
        ]);

        Route::get('json/listDetailGroupOutlet', [
            'as' => 'discounTotOutlet.list', 'uses' => 'DiscountController@jsonListOutletGroupDetailView'
        ]);
        /*
         * Post Routes
         */

        Route::post('add', [
            'as' => 'discounTotOutlet.add', 'uses' => 'DiscountController@addDiscountGroupOutlet'
        ]);

        Route::post('status', [
            'as' => 'discounTotOutlet.status', 'uses' => 'DiscountController@statusGroupOutlet'
        ]);
    });

});