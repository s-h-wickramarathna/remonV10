<?php
use Application\CustomerManage\Http\Controllers\CustomerController;
use Application\CustomerManage\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('customer')->namespace('Application\CustomerManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [CustomerController::class, 'addView'])->name('customer.add');
        Route::get('list', [CustomerController::class, 'listView'])->name('customer.list');
        Route::get('json/list', [CustomerController::class, 'jsonList'])->name('customer.list');
        Route::get('edit/{id}', [CustomerController::class, 'editView'])->name('customer.edit');
        Route::get('report/list', [CustomerController::class, 'reportView'])->name('report.customer.list');
        Route::get('list/download', [CustomerController::class, 'customerReportDownload'])->name('report.customer.list');

        /**
         * POST Routes
         */
        Route::post('add', [CustomerController::class, 'add'])->name('customer.add');
        Route::post('delete', [VehicleController::class, 'delete'])->name('vehicle.delete');
        Route::post('edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('status', [CustomerController::class, 'status'])->name('customer.status');
        Route::post('credit-status', [CustomerController::class, 'creditLimitStatus'])->name('customer.credit_status');
    });
});
