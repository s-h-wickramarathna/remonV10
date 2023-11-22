<?php
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth','web']], function()
{

    Route::group(['prefix' => 'reports/customer', 'namespace' => 'Application\OutletListManagement\Http\Controllers'], function(){
          /**
           * GET Routes
           */

          Route::get('list', [
              'as' => 'outlet-management.list',
              'uses' => 'OutletListManagementController@listView'
          ]);

        Route::get('download', [
            'as' => 'outlet-management.list',
            'uses' => 'OutletListManagementController@download'
        ]);


        Route::get('detail/{id}', [
              'as' => 'outlet-management.details',
              'uses' => 'OutletListManagementController@detailView'
          ]);

          /**
          * POST Routes
          */
          Route::post('changeOutlatLocation', [
            'uses' => 'OutletListManagementController@changeOutlatLocation',
            'as'   => 'changeOutlatLocation'
          ]);

          /**
          * JSON Routes
          */
          Route::get('json/getOutlets', [
              'as' => 'outlet-management.list',
              'uses' => 'OutletListManagementController@getOutlets'
          ]);  

          Route::get('json/getOutletInvoices', [
              'as' => 'outlet-management.list',
              'uses' => 'OutletListManagementController@getOutletInvoices'
          ]);  

        });
});