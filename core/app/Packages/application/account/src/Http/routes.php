<?php

/**
 * USER AUTHENTICATION MIDDLEWARE
 */

use Application\AccountManage\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('account')->namespace('Application\AccountManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [ControllersAccountController::class, 'addView'])->name('account.add');
        Route::get('edit/{id}', [AccountController::class, 'editView'])->name('account.edit'); // Not Implemented Yet
        Route::get('list', [AccountController::class, 'listView'])->name('account.list');
        Route::get('json/list', [AccountController::class, 'jsonList'])->name('product.list');
        Route::get('json/listcode', [AccountController::class, 'listcode'])->name('product.add');
        Route::get('json/listproductcat', [AccountController::class, 'listproductcat'])->name('product.add');
        Route::get('exceltolist', [AccountController::class, 'exceltolist'])->name('product.add');

        /**
         * POST Routes
         */
        Route::post('add', [AccountController::class, 'add'])->name('account.add');
        Route::post('edit/{id}', [AccountController::class, 'edit'])->name('product.edit'); // Not Implemented Yet
        Route::post('status', [AccountController::class, 'status'])->name('product.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});