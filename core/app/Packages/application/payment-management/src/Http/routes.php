<?php

use Application\PaymentManage\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('payment')->namespace('Application\PaymentManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('new/{id}/{type?}', [PaymentController::class, 'newPaymentView'])->name('payment.add');
        Route::get('new', [PaymentController::class, 'newPaymentOutletList'])->name('payment.add');
        Route::get('receipt/list', [PaymentController::class, 'receiptListView'])->name('payment_recipt.list');
        Route::get('receipt/detail/{id}', [PaymentController::class, 'receiptDetailsView'])->name('recipt.list');
        Route::get('receipt/print/{id}', [PaymentController::class, 'receiptPrint'])->name('payment_recipt.print');

        // ... (rest of the GET routes)

        /**
         * JSON Routes
         */
        Route::get('json/getInvoicesFor', [PaymentController::class, 'getInvoicesFor'])->name('payment.json');
        Route::get('json/getOutlets', [PaymentController::class, 'getOutlets'])->name('payment.json');
        Route::get('json/getRecipts', [PaymentController::class, 'getRecipts'])->name('recipt.json');
        Route::post('json/addPayment', [PaymentController::class, 'addPayment'])->name('payment.json');
        Route::get('json/bank/{id}', [PaymentController::class, 'getBank'])->name('payment.json');
    });
});
