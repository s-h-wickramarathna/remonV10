<?php


Route::group(['middleware' => ['auth','admin']], function()
{	
	Route::group(['prefix'=>'admin/message/','namespace' => 'App\Modules\AdminMessageManage\Controllers'],function() {

		Route::get('inbox', [
        	'as' => 'message.inbox', 'uses' => 'AdminMessageManageController@index' 
        ]);

        Route::get('compose', [
        	'as' => 'message.inbox', 'uses' => 'AdminMessageManageController@composeView' 
        ]);

        Route::get('read/{id}', [
        	'as' => 'message.inbox', 'uses' => 'AdminMessageManageController@readView' 
        ]);
	});    
});
