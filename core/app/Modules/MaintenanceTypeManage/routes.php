<?php

Route::group(['middleware' => ['auth','web']], function(){
	
    Route::group(['prefix' => 'admin/maintenance/type', 'namespace' => 'App\Modules\MaintenanceTypeManage\Controllers'],function(){

   	 	Route::get('list', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@index'
      	]);

      	Route::get('show/{id}', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@show'
      	]);

      	Route::get('edit/{id}', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@edit'
      	]);

      	Route::get('add', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@create'
      	]);


      	Route::post('add', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@store'
      	]);

      	Route::post('edit/{id}', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@update'
      	]);

      	Route::delete('delete/{id}', [
       		 'as' => 'maintenance.type.list', 'uses' => 'MaintenanceTypeManageController@destroy'
      	]);
    	
	});  	
}); 

