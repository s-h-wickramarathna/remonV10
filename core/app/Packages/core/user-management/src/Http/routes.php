<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
use Core\UserManage\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'user', 'namespace' => 'Core\UserManage\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [UserController::class, 'addView'])->name('user.add');
        Route::get('edit/{id}', [UserController::class, 'editView'])->name('user.edit');
        Route::get('list', [UserController::class, 'listView'])->name('user.list');
        Route::get('json/list', [UserController::class, 'jsonList'])->name('user.jsonList');
        Route::get('change', [UserController::class, 'changeLogin'])->name('login.change');

        /**
         * POST Routes
         */
        Route::post('add', [UserController::class, 'add'])->name('user.store');
        Route::post('edit/{id}', [UserController::class, 'edit'])->name('user.update');
        Route::post('status', [UserController::class, 'status'])->name('user.status');
        Route::post('delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('change', [UserController::class, 'change'])->name('login.change');
    });
});
