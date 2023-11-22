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
    Route::group(['prefix' => 'productCategory', 'namespace' => 'Application\ProductCategory\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'ProductCategory.add', 'uses' => 'ProductCategoryController@addView'
      ]);
      Route::get('edit/{id}', [
        'as' => 'ProductCategory.edit', 'uses' => 'ProductCategoryController@editView'  // Not Implemented Yet
      ]);

       Route::get('list', [
        'as' => 'ProductCategory.list', 'uses' => 'ProductCategoryController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'ProductCategory.list', 'uses' => 'ProductCategoryController@jsonList'
      ]);

      

      /**
       * POST Routes
       */
       Route::post('add', [
        'as' => 'ProductCategory.add', 'uses' => 'ProductCategoryController@add'
      ]);
         Route::post('status', [
        'as' => 'ProductCategory.status', 'uses' => 'ProductCategoryController@status'
      ]);
          Route::post('edit/{id}', [
        'as' => 'ProductCategory.edit', 'uses' => 'ProductCategoryController@edit'  // Not Implemented Yet
      ]);

    });
});