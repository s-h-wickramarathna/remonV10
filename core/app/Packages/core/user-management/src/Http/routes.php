<?php
/**
 * MENU MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Chathura Chandimal <chandimal@craftbyorange.com>
 * @copyright 2015 Chathura Chandimal
 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'user', 'namespace' => 'Core\UserManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'user.add', 'uses' => 'UserController@addView'
      ]);

      Route::get('edit/{id}', [
          'as' => 'user.edit', 'uses' => 'UserController@editView'
      ]);

      Route::get('list', [
        'as' => 'user.list', 'uses' => 'UserController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'user.list', 'uses' => 'UserController@jsonList'
      ]);

      Route::get('change', [
          'as' => 'login.change', 'uses' => 'UserController@changeLogin'
      ]);

      /**
       * POST Routes
       */
      Route::post('add', [
        'as' => 'user.add', 'uses' => 'UserController@add'
      ]);

      Route::post('edit/{id}', [
          'as' => 'user.edit', 'uses' => 'UserController@edit'
      ]);

      Route::post('status', [
        'as' => 'user.status', 'uses' => 'UserController@status'
      ]);

      Route::post('delete', [
        'as' => 'user.delete', 'uses' => 'UserController@delete'
      ]);

      Route::post('change', [
          'as' => 'login.change', 'uses' => 'UserController@change'
      ]);
    });
});