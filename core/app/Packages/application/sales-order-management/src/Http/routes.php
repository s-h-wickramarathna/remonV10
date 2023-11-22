<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'sales-order', 'namespace' => 'Application\SalesOrderManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'sales.order.add', 'uses' => 'SalesOrderController@addView'
      ]);

      Route::get('list', [
              'as' => 'sales.order.list', 'uses' => 'SalesOrderController@listView'
      ]);

      Route::get('get/{id}', [
              'as' => 'sales.order.list', 'uses' => 'SalesOrderController@getOrderData'
      ]);

      Route::get('discard/{id}', [
              'as' => 'sales.order.list', 'uses' => 'SalesOrderController@discardOrder'
      ]);

      Route::get('json/list', [
              'as' => 'sales.order.list', 'uses' => 'SalesOrderController@getOrderList'
      ]);

      Route::get('edit/{id}', [
              'as' => 'sales.order.edit', 'uses' => 'SalesOrderController@editView'
      ]);

      Route::get('getProductByBrand', [
          'as' => 'sales.order.add', 'uses' => 'SalesOrderController@getProductByBrand'
      ]);

      Route::get('getProductByCategory', [
            'as' => 'sales.order.add', 'uses' => 'SalesOrderController@getProductByCategory'
      ]);

      Route::get('toPrint', [
            'as' => 'sales.order.list', 'uses' => 'SalesOrderController@toPrint'
      ]);

      Route::get('getData', [
           'as' => 'sales.order.list', 'uses' => 'SalesOrderController@getOrderDetail'
      ]);

      Route::get('testReturn', [
          'as' => 'sales.order.list', 'uses' => 'SalesOrderController@testReturnTo'
      ]);

      Route::post('getFreeIssue', [
           'as' => 'sales.order.list', 'uses' => 'SalesOrderController@getFreeIssue'
      ]);

      Route::post('getDiscount', [
           'as' => 'sales.order.list', 'uses' => 'SalesOrderController@getDiscount'
      ]);

      /**
      * POST Routes
      */
      Route::post('add', [
        'as' => 'sales.order.list', 'uses' => 'SalesOrderController@add'
      ]);
    });
});