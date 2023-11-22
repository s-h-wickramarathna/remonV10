<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'account', 'namespace' => 'Application\AccountManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'account.add', 'uses' => 'AccountController@addView'
      ]);
      Route::get('edit/{id}', [
        'as' => 'account.edit', 'uses' => 'AccountController@editView'  // Not Implemented Yet
      ]);

      Route::get('list', [
        'as' => 'account.list', 'uses' => 'AccountController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'product.list', 'uses' => 'AccountController@jsonList'
      ]);

      Route::get('json/listcode', [
          'as' => 'product.add', 'uses' => 'AccountController@listcode'
      ]);
      Route::get('json/listproductcat', [
          'as' => 'product.add', 'uses' => 'AccountController@listproductcat'
      ]);
        Route::get('exceltolist', [
            'as' => 'product.add', 'uses' => 'AccountController@exceltolist'
        ]);

      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'account.add', 'uses' => 'AccountController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'product.edit', 'uses' => 'AccountController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'product.status', 'uses' => 'AccountController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});