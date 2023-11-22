<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function () {

    Route::group(['prefix' => 'configuration', 'namespace' => 'Application\Configuration\Http\Controllers'], function () {
        /**
         * GET Routes
         */
        Route::get('add', [
            'as' => 'configuration.add',
            'uses' => 'ConfigurationController@addView'
        ]);

        Route::get('repActive/list', [
            'as' => 'batch.list',
            'uses' => 'ConfigurationController@listView'
        ]);

        Route::get('repActive/json/list', [
            'as' => 'batch.list',
            'uses' => 'ConfigurationController@jsonList'
        ]);

//        Route::get('edit/{id}', [
//            'as' => 'batch.edit',
//            'uses' => 'BatchPriceController@editView'
//        ]);
//
//        Route::get('listE/{id?}', [
//            'as' => 'batch.edit', 'uses' => 'GrnController@getParentList'
//        ]);
//
//        /**
//         * POST Routes
//         */
//        Route::post('add', [
//            'as' => 'batch.add', 'uses' => 'BatchPriceController@add'
//        ]);
//
//        Route::post('delete', [
//            'as' => 'batch.delete', 'uses' => 'GrnController@delete'
//        ]);
//
//        Route::post('edit/{id}', [
//            'as' => 'batch.edit',
//            'uses' => 'BatchPriceController@edit'
//        ]);

        Route::post('repActive/status', [
            'as' => 'batch.status',
            'uses' => 'ConfigurationController@status'
        ]);
        
        Route::get('/rep/list', [
            'uses' => 'ConfigurationController@repListView',
            'as'   => 'rep.list'
        ]);

        Route::get('/rep/db', [
            'uses'  => 'ConfigurationController@getRepDB',
            'as'    => 'rep.db'
        ]);
    });

    Route::group(['prefix' => 'gcm', 'namespace' => 'Application\WebService\Http\Controllers'], function () {

        Route::post('send', [
            'uses' => 'GcmService@sendCommands',
            'as'   => 'index'
        ]);    
    });
});