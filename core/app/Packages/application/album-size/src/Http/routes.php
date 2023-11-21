<?php
use Application\AlbumSize\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('sizes')->namespace('Application\AlbumSize\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [ControllersSizeController::class, 'addView'])->name('size.add');
        Route::get('edit/{id}', [SizeController::class, 'editView'])->name('size.edit'); // Not Implemented Yet
        Route::get('list', [SizeController::class, 'listView'])->name('size.list');
        Route::get('json/list', [SizeController::class, 'jsonList'])->name('size.list');

        /**
         * POST Routes
         */
        Route::post('add', [SizeController::class, 'add'])->name('size.add');
        Route::post('edit/{id}', [SizeController::class, 'edit'])->name('size.edit'); // Not Implemented Yet
        Route::post('status', [SizeController::class, 'status'])->name('size.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
