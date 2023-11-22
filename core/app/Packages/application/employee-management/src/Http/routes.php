<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'employee/type', 'namespace' => 'Application\EmployeeManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'employee.type.add', 'uses' => 'EmployeeTypeController@addView'
      ]);

      Route::get('list', [
              'as' => 'employee.type.list', 'uses' => 'EmployeeTypeController@listView'
      ]);

      Route::get('json/list', [
              'as' => 'employee.type.list', 'uses' => 'EmployeeTypeController@jsonList'
      ]);

      Route::get('edit/{id}', [
              'as' => 'employee.type.edit', 'uses' => 'EmployeeTypeController@editView'
      ]);

      /**
      * POST Routes
      */
      Route::post('add', [
        'as' => 'employee.type.add', 'uses' => 'EmployeeTypeController@add'
      ]);

      Route::post('delete', [
              'as' => 'employee.type.delete', 'uses' => 'EmployeeTypeController@delete'
      ]);

      Route::post('edit/{id}', [
              'as' => 'employee.type.edit', 'uses' => 'EmployeeTypeController@edit'
      ]);

    });

    /**
    *for employee curd
    */

    Route::group(['prefix' => 'employee', 'namespace' => 'Application\EmployeeManage\Http\Controllers'], function(){
          /**
           * GET Routes
           */
          Route::get('add', [
            'as' => 'employee.add', 'uses' => 'EmployeeController@addView'
          ]);

          Route::get('list', [
                  'as' => 'employee.list', 'uses' => 'EmployeeController@listView'
          ]);

          Route::get('json/list', [
                  'as' => 'employee.list', 'uses' => 'EmployeeController@jsonList'
          ]);

          Route::get('edit/{id}', [
                  'as' => 'employee.edit', 'uses' => 'EmployeeController@editView'
          ]);

          Route::get('barcode/{id}', [
                  'as' => 'employee.list', 'uses' => 'EmployeeController@genBarCode'
          ]);

          Route::get('listE/{id?}', [
                  'as' => 'employee.edit', 'uses' => 'EmployeeController@getParentList'
          ]);

          Route::get('filter', [
                  'as' => 'employee.edit', 'uses' => 'EmployeeController@filter'
          ]);

          /*
          * get data to data table view all form
          */
          Route::get('view', [
            'as' => 'employee.list', 'uses' => 'EmployeeController@getViewData'
          ]);

          Route::get('getParent', [
            'as' => 'employee.add', 'uses' => 'EmployeeController@getParent'
          ]);

        /**
          * POST Routes
          */
          Route::post('add', [
            'as' => 'employee.add', 'uses' => 'EmployeeController@add'
          ]);

          Route::post('delete', [
                  'as' => 'employee.delete', 'uses' => 'EmployeeController@delete'
          ]);

          Route::post('edit/{id}', [
                  'as' => 'employee.edit', 'uses' => 'EmployeeController@edit'
          ]);

          Route::post('status', [
                  'as' => 'employee.status', 'uses' => 'EmployeeController@status'
          ]);

        });
});