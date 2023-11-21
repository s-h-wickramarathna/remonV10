<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
use Core\Permissions\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'permission', 'namespace' => 'Core\Permissions\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('list', [PermissionController::class, 'listView'])->name('permission.list');
        Route::get('json/list', [PermissionController::class, 'jsonList'])->name('permission.jsonList');
        Route::get('api/list', [PermissionController::class, 'apiList'])->name('permission.apiList');

        /**
         * POST Routes
         */
        Route::post('status', [PermissionController::class, 'status'])->name('permission.status');
        Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
