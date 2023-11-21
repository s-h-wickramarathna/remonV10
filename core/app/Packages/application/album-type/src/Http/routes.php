<?php
use Application\AlbumType\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('albumType')->namespace('Application\AlbumType\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [TypeController::class, 'addView'])->name('albumType.add');
        Route::get('edit/{id}', [TypeController::class, 'editView'])->name('albumType.edit'); // Not Implemented Yet
        Route::get('list', [TypeController::class, 'listView'])->name('albumType.list');
        Route::get('json/list', [TypeController::class, 'jsonList'])->name('albumType.list');

        /**
         * POST Routes
         */
        Route::post('add', [TypeController::class, 'add'])->name('albumType.add');
        Route::post('edit/{id}', [TypeController::class, 'edit'])->name('albumType.edit'); // Not Implemented Yet
        Route::post('status', [TypeController::class, 'status'])->name('albumType.status');
        Route::post('delete', [TypeController::class, 'delete'])->name('albumType.delete');
    });
});
