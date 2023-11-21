<?php
use Application\LaminateType\Http\Controllers\LaminateController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('laminateType')->namespace('Application\LaminateType\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [LaminateController::class, 'addView'])->name('laminate.add');
        Route::get('edit/{id}', [LaminateController::class, 'editView'])->name('laminate.edit'); // Not Implemented Yet
        Route::get('list', [LaminateController::class, 'listView'])->name('laminate.list');
        Route::get('json/list', [LaminateController::class, 'jsonList'])->name('laminate.list');

        /**
         * POST Routes
         */
        Route::post('add', [LaminateController::class, 'add'])->name('laminate.add');
        Route::post('edit/{id}', [LaminateController::class, 'edit'])->name('laminate.edit');
        Route::post('status', [LaminateController::class, 'status'])->name('laminate.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
