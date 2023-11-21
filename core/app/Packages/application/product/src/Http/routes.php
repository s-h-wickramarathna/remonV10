<?php
use Application\Product\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('product')->namespace('Application\Product\Http\Controllers')->group(function () {

        /**
         * GET Routes
         */
        Route::get('add', [ProductController::class, 'addView'])->name('product.add');
        Route::get('edit/{id}', [ProductController::class, 'editView'])->name('product.edit'); // Not Implemented Yet
        Route::get('list', [ProductController::class, 'listView'])->name('product.list');
        Route::get('json/list', [ProductController::class, 'jsonList'])->name('product.list');
        Route::get('catadd', [ProductController::class, 'addCat'])->name('product.catadd');
        Route::get('json/listcode', [ProductController::class, 'listcode'])->name('product.json.listcode');
        Route::get('json/listproductcat', [ProductController::class, 'listproductcat'])->name('product.json.listproductcat');
        Route::get('exceltolist', [ProductController::class, 'exceltolist'])->name('product.exceltolist');

        /**
         * POST Routes
         */
        Route::post('add', [ProductController::class, 'add'])->name('product.add');
        Route::post('edit/{id}', [ProductController::class, 'edit'])->name('product.edit'); // Not Implemented Yet
        Route::post('status', [ProductController::class, 'status'])->name('product.status');
        // Route::post('delete', [
        //     'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
        // ]);
    });
});
