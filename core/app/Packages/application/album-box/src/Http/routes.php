<?php
use Application\AlbumBox\Http\Controllers\BoxController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('albumbox')->namespace('Application\AlbumBox\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [BoxController::class, 'addView'])->name('box.add');
        Route::get('edit/{id}', [BoxController::class, 'editView'])->name('box.edit'); // Not Implemented Yet
        Route::get('list', [BoxController::class, 'listView'])->name('box.list');
        Route::get('json/list', [BoxController::class, 'jsonList'])->name('box.list');

        /**
         * POST Routes
         */
        Route::post('add', [BoxController::class, 'add'])->name('box.add');
        Route::post('edit/{id}', [BoxController::class, 'edit'])->name('box.edit');
        Route::post('status', [BoxController::class, 'status'])->name('box.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
