<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
use Core\UserRoles\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'user/role', 'namespace' => 'Core\UserRoles\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [UserRoleController::class, 'addView'])->name('user.role.add');
        Route::get('edit/{id}', [UserRoleController::class, 'editView'])->name('user.role.edit');
        Route::get('list', [UserRoleController::class, 'listView'])->name('user.role.list');
        Route::get('json/list', [UserRoleController::class, 'jsonList'])->name('user.role.jsonList');

        /**
         * POST Routes
         */
        Route::post('add', [UserRoleController::class, 'add'])->name('user.role.store');
        Route::post('edit/{id}', [UserRoleController::class, 'edit'])->name('user.role.update');
        Route::post('delete', [UserRoleController::class, 'delete'])->name('user.role.delete');
    });
});
