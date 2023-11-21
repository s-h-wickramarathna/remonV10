<?php

Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'admin/vehicle', 'namespace' => 'App\Modules\VehicleManage\Controllers'],function(){

		Route::get('index', [
        	'as' => 'admin.vehicle.index', 'uses' => 'VehicleManageController@index' 
        ]);

        Route::get('create', [
        	'as' => 'admin.vehicle.create', 'uses' => 'VehicleManageController@create' 
        ]);

        Route::get('edit/{id}', [
        	'as' => 'admin.vehicle.edit', 'uses' => 'VehicleManageController@edit' 
        ]);

        Route::post('store', [
        	'as' => 'admin.vehicle.store', 'uses' => 'VehicleManageController@store' 
        ]);

        Route::post('edit/{id}', [
            'as' => 'admin.vehicle.edit', 'uses' => 'VehicleManageController@update' 
        ]);

        Route::post('unassign', [
            'as' => 'admin.vehicle.unassign', 'uses' => 'VehicleManageController@unassign' 
        ]);

        Route::post('assign', [
        	'as' => 'admin.vehicle.assign', 'uses' => 'VehicleManageController@assign' 
        ]);

        Route::post('inactive', [
            'as' => 'admin.vehicle.inactive', 'uses' => 'VehicleManageController@deactivate' 
        ]);

        Route::post('active', [
            'as' => 'admin.vehicle.active', 'uses' => 'VehicleManageController@activate' 
        ]);

        Route::post('upload', [
            'as' => 'admin.vehicle.upload', 'uses' => 'VehicleManageController@upload' 
        ]);
	});  	
});