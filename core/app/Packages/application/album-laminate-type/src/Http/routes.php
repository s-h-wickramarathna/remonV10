<?php
/**

 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'laminateType', 'namespace' => 'Application\LaminateType\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'laminate.add', 'uses' => 'LaminateController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'laminate.edit', 'uses' => 'LaminateController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'laminate.list', 'uses' => 'LaminateController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'laminate.list', 'uses' => 'LaminateController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'laminate.add', 'uses' => 'LaminateController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'laminate.edit', 'uses' => 'LaminateController@edit'
      ]);

      Route::post('status', [
        'as' => 'laminate.status', 'uses' => 'LaminateController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});