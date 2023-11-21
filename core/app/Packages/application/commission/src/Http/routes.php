<?php
use Application\CommissionManage\Http\Controllers\CommissionController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('commission')->namespace('Application\CommissionManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [CommissionController::class, 'addView'])->name('commission.add');
        Route::get('edit/{id}', [CommissionController::class, 'editView'])->name('commission.edit'); // Not Implemented Yet
        Route::get('list', [CommissionController::class, 'listView'])->name('commission.list');
        Route::get('json/list', [CommissionController::class, 'jsonList'])->name('product.list');
        Route::get('json/listcode', [CommissionController::class, 'listcode'])->name('product.add');
        Route::get('json/listproductcat', [CommissionController::class, 'listproductcat'])->name('product.add');
        Route::get('exceltolist', [CommissionController::class, 'exceltolist'])->name('product.add');

        /**
         * POST Routes
         */
        Route::post('add', [CommissionController::class, 'add'])->name('commission.add');
        Route::post('edit/{id}', [CommissionController::class, 'edit'])->name('product.edit'); // Not Implemented Yet
        Route::post('status', [CommissionController::class, 'status'])->name('product.status');

        // Uncomment the following route if needed
        // Route::post('delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
});
