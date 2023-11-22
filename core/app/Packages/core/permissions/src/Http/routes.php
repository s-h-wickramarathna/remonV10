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
    Route::group(['prefix' => 'permission', 'namespace' => 'Core\Permissions\Http\Controllers'], function(){
      /**
       * GET Routes
       */
//      Route::get('edit/{id}', [
//        'as' => 'menu.edit', 'uses' => 'MenuController@editView'  // Not Implemented Yet
//      ]);

      Route::get('list', [
        'as' => 'permission.list', 'uses' => 'PermissionController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'permission.list', 'uses' => 'PermissionController@jsonList'
      ]);

      Route::get('api/list', [
        'as' => 'permission.list', 'uses' => 'PermissionController@apiList'
      ]);

      /**
       * POST Routes
       */
//      Route::post('edit/{id}', [
//        'as' => 'menu.edit', 'uses' => 'MenuController@edit'  // Not Implemented Yet
//      ]);

      Route::post('status', [
        'as' => 'permission.status', 'uses' => 'PermissionController@status'
      ]);

      Route::post('delete', [
        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
      ]);
    });
});