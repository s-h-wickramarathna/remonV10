<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
use Core\PermissionGroups\Http\Controllers\PermissionGroupsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'permission/groups', 'namespace' => 'Core\PermissionGroups\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [PermissionGroupsController::class, 'addView'])->name('permission.groups.add');
        Route::get('list', [PermissionGroupsController::class, 'jsonList'])->name('permission.groups.list');

        /**
         * POST Routes
         */
        Route::post('add', [PermissionGroupsController::class, 'addGroup'])->name('permission.groups.store');
    });
});
