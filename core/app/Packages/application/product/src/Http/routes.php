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
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'product', 'namespace' => 'Application\Product\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'product.add', 'uses' => 'ProductController@addView'
      ]);
      Route::get('edit/{id}', [
        'as' => 'product.edit', 'uses' => 'ProductController@editView'  // Not Implemented Yet
      ]);

      Route::get('list', [
        'as' => 'product.list', 'uses' => 'ProductController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'product.list', 'uses' => 'ProductController@jsonList'
      ]);
       Route::get('catadd', [
        'as' => 'product.catadd', 'uses' => 'ProductController@addCat'
      ]);
      Route::get('json/listcode', [
          'as' => 'product.add', 'uses' => 'ProductController@listcode'
      ]);
      Route::get('json/listproductcat', [
          'as' => 'product.add', 'uses' => 'ProductController@listproductcat'
      ]);
        Route::get('exceltolist', [
            'as' => 'product.add', 'uses' => 'ProductController@exceltolist'
        ]);

      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'product.add', 'uses' => 'ProductController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'product.edit', 'uses' => 'ProductController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'product.status', 'uses' => 'ProductController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});