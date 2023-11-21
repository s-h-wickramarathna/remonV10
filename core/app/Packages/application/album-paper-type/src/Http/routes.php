<?php
use Application\PaperType\Http\Controllers\PaperTypeController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('paperType')->namespace('Application\PaperType\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [PaperTypeController::class, 'addView'])->name('paperType.add');
        Route::get('edit/{id}', [PaperTypeController::class, 'editView'])->name('paperType.edit'); // Not Implemented Yet
        Route::get('list', [PaperTypeController::class, 'listView'])->name('paperType.list');
        Route::get('json/list', [PaperTypeController::class, 'jsonList'])->name('paperType.list');

        /**
         * POST Routes
         */
        Route::post('add', [PaperTypeController::class, 'add'])->name('paperType.add');
        Route::post('edit/{id}', [PaperTypeController::class, 'edit'])->name('paperType.edit'); // Not Implemented Yet
        Route::post('status', [PaperTypeController::class, 'status'])->name('paperType.status');
        Route::post('delete', [PaperTypeController::class, 'delete'])->name('paperType.delete');
    });
});
