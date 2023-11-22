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
    Route::group(['prefix' => 'sizes', 'namespace' => 'Application\AlbumSize\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'size.add', 'uses' => 'SizeController@addView'
      ]);
      Route::get('edit/{id}', [
        'as' => 'size.edit', 'uses' => 'SizeController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'size.list', 'uses' => 'SizeController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'size.list', 'uses' => 'SizeController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'size.add', 'uses' => 'SizeController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'size.edit', 'uses' => 'SizeController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'size.status', 'uses' => 'SizeController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});