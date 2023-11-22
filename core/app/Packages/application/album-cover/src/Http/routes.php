<?php
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'albumCover', 'namespace' => 'Application\AlbumCover\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'cover.add', 'uses' => 'CoverController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'cover.edit', 'uses' => 'CoverController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'cover.list', 'uses' => 'CoverController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'cover.list', 'uses' => 'CoverController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'cover.add', 'uses' => 'CoverController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'cover.edit', 'uses' => 'CoverController@edit'
      ]);

      Route::post('status', [
        'as' => 'cover.status', 'uses' => 'CoverController@status'
      ]);

//      Route::post('delete', [
//        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
//      ]);
    });
});