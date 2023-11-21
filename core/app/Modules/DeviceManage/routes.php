<?php

Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'admin/device', 'namespace' => 'App\Modules\DeviceManage\Controllers'],function(){

        /****GET*****/

        Route::get('index', [
            'as' => 'admin.device.index', 'uses' => 'DeviceManageController@index' 
        ]);

        Route::get('create', [
            'as' => 'admin.device.create', 'uses' => 'DeviceManageController@create' 
        ]);

        Route::get('edit/{id}', [
            'as' => 'admin.device.edit', 'uses' => 'DeviceManageController@edit' 
        ]);

        /****POST*****/

        Route::post('store', [
            'as' => 'admin.device.store', 'uses' => 'DeviceManageController@store' 
        ]);

        Route::post('edit/{id}', [
            'as' => 'admin.device.edit', 'uses' => 'DeviceManageController@update' 
        ]);

        Route::post('inactive', [
            'as' => 'admin.device.inactive', 'uses' => 'DeviceManageController@deactivate' 
        ]);

        Route::post('active', [
            'as' => 'admin.device.active', 'uses' => 'DeviceManageController@activate' 
        ]);

        Route::post('upload', [
            'as' => 'admin.device.upload', 'uses' => 'DeviceManageController@upload' 
        ]);
	});  	
});