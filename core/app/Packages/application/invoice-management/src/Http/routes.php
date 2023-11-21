<?php
use Illuminate\Support\Facades\Route;
use Application\InvoiceManage\Http\Controllers\InvoiceController;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('invoice')->namespace('Application\InvoiceManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add/{id}', [InvoiceController::class, 'addView'])->name('invoice.add');
        Route::get('admin_authentication', [InvoiceController::class, 'adminAuthentication'])->name('invoice.admin_authentication');
        Route::get('print', [InvoiceController::class, 'toPrint'])->name('invoice.print');
        Route::get('toPrint', [InvoiceController::class, 'toPrint'])->name('invoice.toPrint');
        Route::get('list', [InvoiceController::class, 'invoiceList'])->name('invoice.list');
        Route::get('customerlist', [InvoiceController::class, 'listOutlet'])->name('invoice.customerlist');
        Route::get('search', [InvoiceController::class, 'search'])->name('invoice.search');
        Route::get('add/getData/{id}', [InvoiceController::class, 'getData'])->name('invoice.getData');
        Route::get('pending-approve', [InvoiceController::class, 'pendingApprove'])->name('invoice.pending-approve');
        Route::get('approve', [InvoiceController::class, 'approve'])->name('invoice.approve');
        Route::get('get/credit/{id}', [InvoiceController::class, 'getCreditData'])->name('invoice.getCreditData');
        Route::get('reject', [InvoiceController::class, 'reject'])->name('invoice.reject');

        /**
         * JSON Routes
         */
        Route::get('json/getOutlets', [InvoiceController::class, 'getOutlets'])->name('invoice.getOutlets');
        Route::get('json/getInvoices', [InvoiceController::class, 'getInvoices'])->name('invoice.getInvoices');

        Route::get('getProductByBrand', [InvoiceController::class, 'getProductByBrand'])->name('invoice.getProductByBrand');
        Route::get('getProductByCategory', [InvoiceController::class, 'getProductByCategory'])->name('invoice.getProductByCategory');
        Route::get('getProductByRange', [InvoiceController::class, 'getProductByRange'])->name('invoice.getProductByRange');
        Route::get('getMarketeer', [InvoiceController::class, 'getMarketeer'])->name('invoice.getMarketeer');
        Route::get('aging/download', [InvoiceController::class, 'agingDownload'])->name('invoice.agingDownload');
        Route::get('aging/excel', [InvoiceController::class, 'agingExcel'])->name('invoice.agingExcel');
        Route::get('payment-aging', [InvoiceController::class, 'paymentAging'])->name('invoice.payment-aging');
        Route::get('payment-aging/excel', [InvoiceController::class, 'paymentAgingExcel'])->name('invoice.paymentAgingExcel');

        /**
         * POST Routes
         */
        Route::post('credit/add', [InvoiceController::class, 'addCreditNote'])->name('invoice.addCreditNote');
        Route::post('add', [InvoiceController::class, 'add'])->name('invoice.add');
        Route::post('amend/add', [InvoiceController::class, 'addAmendInvoice'])->name('invoice.addAmendInvoice');
        Route::post('new/add', [InvoiceController::class, 'addNewInvoice'])->name('invoice.addNewInvoice');
        Route::post('delete', [InvoiceController::class, 'delete'])->name('invoice.delete');
    });
});
