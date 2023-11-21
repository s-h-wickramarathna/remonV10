<?php
/**
 * LIVE TRACKING MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Tharindu Lakshan <info.tharindu.mac@gmail.com>
 * @copyright 2015 Tharindu Lakshan
 */

/**
 * LIVE TRACKING 
 */
Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'admin/tracking', 'namespace' => 'App\Modules\MultiMapManage\Controllers'], function(){
      /**
       * GET
       */
      Route::get('multimap', [
        'as' => 'gps.tracking.multimap', 'uses' => 'MultiMapManageController@index'
      ]);

 
      /**
       * POST
       */
     
    });
});