<?php
use Application\AlbumCover\Http\Controllers\CoverController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('albumCover')->namespace('Application\AlbumCover\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [CoverController::class, 'addView'])->name('cover.add');
        Route::get('edit/{id}', [CoverController::class, 'editView'])->name('cover.edit'); // Not Implemented Yet
        Route::get('list', [CoverController::class, 'listView'])->name('cover.list');
        Route::get('json/list', [CoverController::class, 'jsonList'])->name('cover.list');

        /**
         * POST Routes
         */
        Route::post('add', [CoverController::class, 'add'])->name('cover.add');
        Route::post('edit/{id}', [CoverController::class, 'edit'])->name('cover.edit');
        Route::post('status', [CoverController::class, 'status'])->name('cover.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
