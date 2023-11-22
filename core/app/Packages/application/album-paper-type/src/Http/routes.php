<?php
/**

 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'paperType', 'namespace' => 'Application\PaperType\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'paperType.add', 'uses' => 'PaperTypeController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'paperType.edit', 'uses' => 'PaperTypeController@editView'  // Not Implemented Yet
      ]);
      Route::get('list', [
        'as' => 'paperType.list', 'uses' => 'PaperTypeController@listView'
      ]);

        Route::get('json/list', [
            'as' => 'paperType.list', 'uses' => 'PaperTypeController@jsonList'
        ]);



      /**
       * POST Routes
       */
        Route::post('add', [
        'as' => 'paperType.add', 'uses' => 'PaperTypeController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'paperType.edit', 'uses' => 'PaperTypeController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'paperType.status', 'uses' => 'PaperTypeController@status'
      ]);

      Route::post('delete', [
        'as' => 'paperType.delete', 'uses' => 'PermissionController@delete'
      ]);
    });
});