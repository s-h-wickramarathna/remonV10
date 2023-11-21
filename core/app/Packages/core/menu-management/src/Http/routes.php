<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
use Core\MenuManage\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'menu', 'namespace' => 'Core\MenuManage\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [MenuController::class, 'addView'])->name('menu.add');
        Route::get('edit/{id}', [MenuController::class, 'editView'])->name('menu.edit');
        Route::get('list', [MenuController::class, 'listView'])->name('menu.list');
        Route::get('json/list', [MenuController::class, 'jsonList'])->name('menu.jsonList');

        /**
         * POST Routes
         */
        Route::post('add', [MenuController::class, 'add'])->name('menu.store');
        Route::post('edit/{id}', [MenuController::class, 'edit'])->name('menu.update');
        Route::post('status', [MenuController::class, 'status'])->name('menu.status');
        Route::post('delete', [MenuController::class, 'delete'])->name('menu.delete');
    });
});
