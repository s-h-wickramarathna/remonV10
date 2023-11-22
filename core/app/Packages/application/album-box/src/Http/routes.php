<?php
/**

 */

use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'albumbox', 'namespace' => 'Application\AlbumBox\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'box.add', 'uses' => 'BoxController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'box.edit', 'uses' => 'BoxController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'box.list', 'uses' => 'BoxController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'box.list', 'uses' => 'BoxController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'box.add', 'uses' => 'BoxController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'box.edit', 'uses' => 'BoxController@edit'
      ]);

      Route::post('status', [
        'as' => 'box.status', 'uses' => 'BoxController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});