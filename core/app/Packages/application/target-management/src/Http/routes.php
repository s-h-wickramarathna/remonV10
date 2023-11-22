<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'marketeerTarget', 'namespace' => 'Application\TargetManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'marketeer.target.add', 'uses' => 'TargetController@addView'
      ]);

      Route::get('list', [
              'as' => 'marketeer.target.list', 'uses' => 'TargetController@listView'
      ]);

      Route::get('json/list', [
              'as' => 'marketeer.target.list', 'uses' => 'TargetController@jsonList'
      ]);

      Route::get('edit/{id}', [
              'as' => 'marketeer.target.edit', 'uses' => 'TargetController@editView'
      ]);

      /*
       * get data of filtered value in data table
       */
      Route::get('filter', [
           'as' => 'marketeer.target.list', 'uses' => 'TargetController@filter'
      ]);

      /**
      * POST Routes
      */
      Route::post('add', [
        'as' => 'marketeer.target.add', 'uses' => 'TargetController@add'
      ]);

      Route::post('delete', [
              'as' => 'rep.target.delete', 'uses' => 'TargetController@delete'
      ]);

      Route::post('edit/{id}', [
              'as' => 'rep.target.edit', 'uses' => 'TargetController@edit'
      ]);

    });
});