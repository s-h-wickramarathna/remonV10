<?php
/**
 * PERMISSION GROUPS ROUTES
 *
 * @version 1.0.0
 * @author aruna wijerathna <arunaswj@gmail.com>
 * @copyright 2015 Aruna Wijerathna
 */
use Illuminate\Support\Facades\Route;
/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'permission/groups', 'namespace' => 'Core\PermissionGroups\Http\Controllers'], function(){

      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'permission.groups.add', 'uses' => 'PermissionGroupsController@addView'
      ]);

      Route::get('list',[
      	'as' => 'permission.groups.list', 'uses' => 'PermissionGroupsController@jsonList'
      ]);

      /**
       * POST Routes
       */
      Route::post('add', [
        'as' => 'permission.groups.add', 'uses' => 'PermissionGroupsController@addGroup'
      ]);

    });
});