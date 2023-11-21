<?php
/**
 * LIVE TRACKING MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Tharindu Lakshan <info.tharindu.mac@gmail.com>
 * @copyright 2015 Tharindu Lakshan
 */

use Illuminate\Support\Facades\Route;

/**
 * LIVE TRACKING 
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'admin/tracking', 'namespace' => 'App\Modules\LiveTrackingManage\Controllers'], function(){
      /**
       * GET
       */
      Route::get('live/{imei}', [
        'as' => 'gps.tracking.live', 'uses' => 'LiveTrackingManageController@liveWithDevice'
      ]);

      /**
       * GET
       */
      Route::get('live', [
        'as' => 'gps.tracking.live', 'uses' => 'LiveTrackingManageController@liveWithoutDevice'
      ]);

 
      /**
       * POST
       */


       /**
       * JSON
       */
      Route::get('json/get_devices', [
        'as' => 'gps.tracking.live', 'uses' => 'LiveTrackingManageController@get_devices'
      ]);

      Route::get('json/get_vehicle', [
        'as' => 'gps.tracking.live', 'uses' => 'LiveTrackingManageController@get_vehicle'
      ]);

      Route::get('json/get_locations', [
        'as' => 'gps.tracking.live', 'uses' => 'LiveTrackingManageController@get_locations'
      ]);
     
    });
});