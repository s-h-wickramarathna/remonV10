<?php
/**

 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'albumType', 'namespace' => 'Application\AlbumType\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'albumType.add', 'uses' => 'TypeController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'albumType.edit', 'uses' => 'TypeController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'albumType.list', 'uses' => 'TypeController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'albumType.list', 'uses' => 'TypeController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'albumType.add', 'uses' => 'TypeController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'albumType.edit', 'uses' => 'TypeController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'albumType.status', 'uses' => 'TypeController@status'
      ]);

      Route::post('delete', [
        'as' => 'albumType.delete', 'uses' => 'PermissionController@delete'
      ]);
    });
});