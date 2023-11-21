<?php
/**
 * PLAYBACK TRACKING MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Tharindu Lakshan <info.tharindu.mac@gmail.com>
 * @copyright 2015 Tharindu Lakshan
 */

/**
 * PLAYBACK TRACKING 
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'admin/tracking', 'namespace' => 'App\Modules\PlaybackManage\Controllers'], function(){
  
      /**
       * GET
       */
      Route::get('playback/{imei}', [
        'as' => 'gps.tracking.playback', 'uses' => 'PlaybackManageController@liveWithDevice'
      ]);

      /**
       * GET
       */
      Route::get('playback', [
        'as' => 'gps.tracking.playback', 'uses' => 'PlaybackManageController@liveWithoutDevice'
      ]);

 
      /**
       * JSON
       */
      Route::get('json/get_playback_data', [
        'as' => 'gps.tracking.playback', 'uses' => 'PlaybackManageController@getPlaybackData'
      ]);
     
    });
});