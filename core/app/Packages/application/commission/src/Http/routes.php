<?php

use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'commission', 'namespace' => 'Application\CommissionManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'commission.add', 'uses' => 'CommissionController@addView'
      ]);
      Route::get('edit/{id}', [
        'as' => 'commission.edit', 'uses' => 'CommissionController@editView'  // Not Implemented Yet
      ]);

      Route::get('list', [
        'as' => 'commission.list', 'uses' => 'CommissionController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'product.list', 'uses' => 'CommissionController@jsonList'
      ]);

      Route::get('json/listcode', [
          'as' => 'product.add', 'uses' => 'CommissionController@listcode'
      ]);
      Route::get('json/listproductcat', [
          'as' => 'product.add', 'uses' => 'CommissionController@listproductcat'
      ]);
        Route::get('exceltolist', [
            'as' => 'product.add', 'uses' => 'CommissionController@exceltolist'
        ]);

      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'commission.add', 'uses' => 'CommissionController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'product.edit', 'uses' => 'CommissionController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'product.status', 'uses' => 'CommissionController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});