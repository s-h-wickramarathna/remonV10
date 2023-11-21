<?php
/*===========================
Author: lahiru dilshan
Email: lahiru4unew4@gmail.com
===========================*/


Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'admin/location', 'namespace' => 'App\Modules\locationManage\Controllers'],function(){

   	 	/****GET*****/

        Route::get('/', [
			'as'   => 'admin.location.index', 
			'uses' => 'LocationManageController@index' 
        ]);

        Route::get('create', [
			'as'   => 'admin.location.create', 
			'uses' => 'LocationManageController@create' 
        ]);

        Route::get('edit/{id}', [
			'as'   => 'admin.location.edit', 
			'uses' => 'LocationManageController@edit' 
        ]);

        Route::get('show/{id}', [
			'as'   => 'admin.location.view', 
			'uses' => 'LocationManageController@show' 
        ]);

        Route::get('delete/{id}', [
			'as'   => 'admin.location.delete', 
			'uses' => 'LocationManageController@destroy' 
        ]);

        /****POST*****/

        Route::post('store', [
			'as'   => 'admin.location.store', 
			'uses' => 'LocationManageController@store' 
        ]);

        Route::post('edit/{id}', [
			'as'   => 'admin.location.edit', 
			'uses' => 'LocationManageController@update' 
        ]);

        Route::post('inactive', [
			'as'   => 'admin.location.inactive', 
			'uses' => 'LocationManageController@deactivate' 
        ]);

        Route::post('active', [
			'as'   => 'admin.location.active', 
			'uses' => 'LocationManageController@activate' 
        ]);

        Route::post('upload', [
			'as'   => 'admin.location.upload', 
			'uses' => 'LocationManageController@upload' 
        ]);
    	
	});  	
}); 
