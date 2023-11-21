<?php
/*===========================
Author: lahiru dilshan
Email: lahiru4unew4@gmail.com
===========================*/


Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'admin/location/type', 'namespace' => 'App\Modules\LocationTypeManage\Controllers'],function(){

   	 	/****GET*****/

        Route::get('/', [
            'as' => 'admin.location.type.index', 'uses' => 'LocationTypeManageController@index' 
        ]);

        Route::get('create', [
            'as' => 'admin.location.type.create', 'uses' => 'LocationTypeManageController@create' 
        ]);

        Route::get('edit/{id}', [
            'as' => 'admin.location.type.edit', 'uses' => 'LocationTypeManageController@edit' 
        ]);

        Route::get('show/{id}', [
            'as' => 'admin.location.type.view', 'uses' => 'LocationTypeManageController@show' 
        ]);

        Route::get('delete/{id}', [
            'as' => 'admin.location.type.delete', 'uses' => 'LocationTypeManageController@destroy' 
        ]);

        /****POST*****/

        Route::post('store', [
            'as' => 'admin.location.type.store', 'uses' => 'LocationTypeManageController@store' 
        ]);

        Route::post('edit/{id}', [
            'as' => 'admin.location.type.edit', 'uses' => 'LocationTypeManageController@update' 
        ]);

        Route::post('inactive', [
            'as' => 'admin.location.type.inactive', 'uses' => 'LocationTypeManageController@deactivate' 
        ]);

        Route::post('active', [
            'as' => 'admin.location.type.active', 'uses' => 'LocationTypeManageController@activate' 
        ]);

        Route::post('upload', [
            'as' => 'admin.location.type.upload', 'uses' => 'LocationTypeManageController@upload' 
        ]);
    	
	});  	
}); 

