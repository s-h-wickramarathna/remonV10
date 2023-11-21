<?php
use Application\OutletListManagement\Http\Controllers\OutletListManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('reports/customer')->namespace('Application\OutletListManagement\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('list', [OutletListManagementController::class, 'listView'])->name('outlet-management.list');
        Route::get('download', [OutletListManagementController::class, 'download'])->name('outlet-management.list');
        Route::get('detail/{id}', [OutletListManagementController::class, 'detailView'])->name('outlet-management.details');

        /**
         * POST Routes
         */
        Route::post('changeOutlatLocation', [OutletListManagementController::class, 'changeOutlatLocation'])->name('changeOutlatLocation');

        /**
         * JSON Routes
         */
        Route::get('json/getOutlets', [OutletListManagementController::class, 'getOutlets'])->name('outlet-management.list');
        Route::get('json/getOutletInvoices', [OutletListManagementController::class, 'getOutletInvoices'])->name('outlet-management.list');
    });
});
