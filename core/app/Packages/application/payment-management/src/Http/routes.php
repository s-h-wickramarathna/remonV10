<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'payment', 'namespace' => 'Application\PaymentManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('new/{id}/{type?}', [
        'as' => 'payment.add', 'uses' => 'PaymentController@newPaymentView'
      ]);

      Route::get('new', [
        'as' => 'payment.add', 'uses' => 'PaymentController@newPaymentOutletList'
      ]);

      Route::get('receipt/list', [
        'as' => 'payment_recipt.list', 'uses' => 'PaymentController@receiptListView'
      ]);
      
      Route::get('receipt/detail/{id}', [
        'as' => 'recipt.list', 'uses' => 'PaymentController@receiptDetailsView'
      ]);

      Route::get('receipt/print/{id}', [
        'as' => 'payment_recipt.print', 'uses' => 'PaymentController@receiptPrint'
      ]);

        Route::get('aging', [
            'as' => 'payment.report', 'uses' => 'PaymentController@agingReport'
        ]);

        Route::get('aging/download', [
            'as' => 'payment.report', 'uses' => 'PaymentController@download'
        ]);

        Route::get('aging/excel', [
            'as' => 'payment.report', 'uses' => 'PaymentController@excel'
        ]);

        Route::get('cheque/list', [
            'as' => 'payment.cheque.list', 'uses' => 'PaymentController@chequeList'
        ]);

        Route::post('cheque/change-status', [
            'as' => 'payment.cheque.list', 'uses' => 'PaymentController@changeStatus'
        ]);

        Route::get('cheque/download', [
            'as' => 'payment.cheque.list', 'uses' => 'PaymentController@chequeDownload'
        ]);

        Route::get('cheque/excel', [
            'as' => 'payment.cheque.list', 'uses' => 'PaymentController@chequeExcel'
        ]);

        Route::get('receipt/download', [
            'as' => 'payment.list', 'uses' => 'PaymentController@receiptDownload'
        ]);

        Route::get('transaction/list', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@receiptList'
        ]);

        Route::get('report/monthly', [
            'as' => 'payment.report.monthly', 'uses' => 'PaymentController@monthly_collection'
        ]);

        Route::get('transaction/list/download', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@receiptListDownload'
        ]);

        Route::get('transaction/list/excel', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@receiptListExcel'
        ]);

        Route::get('report/list', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@paymentList'
        ]);

        Route::get('receipt/list/download', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@paymentListDownload'
        ]);

        Route::get('receipt/list/excel', [
            'as' => 'payment.report.list', 'uses' => 'PaymentController@paymentListExcel'
        ]);

        Route::get('report/monthly/download', [
            'as' => 'payment.report.monthly', 'uses' => 'PaymentController@monthlyDownload'
        ]);

		Route::get('report/monthly/excel', [
            'as' => 'payment.report.monthly', 'uses' => 'PaymentController@monthlyExcel'
        ]);

        Route::get('report/cashPayment/list', [
            'as' => 'payment.cash.list', 'uses' => 'PaymentController@cashPaymentList'
        ]);

        Route::get('report/cashPayment/download', [
            'as' => 'payment.cash.list', 'uses' => 'PaymentController@cashPaymentDownload'
        ]);

        Route::get('report/cashPayment/excel', [
            'as' => 'payment.cash.list', 'uses' => 'PaymentController@cashPaymentExcel'
        ]);

        Route::get('report/onlinePayment/list', [
            'as' => 'payment.onlinePayment.list', 'uses' => 'PaymentController@onlinePaymentList'
        ]);

        Route::get('report/onlinePayment/download', [
            'as' => 'payment.onlinePayment.list', 'uses' => 'PaymentController@onlinePaymentDownload'
        ]);

        Route::get('report/onlinePayment/excel', [
            'as' => 'payment.onlinePayment.list', 'uses' => 'PaymentController@onlinePaymentExcel'
        ]);

        Route::get('report/cashDeposit/list', [
            'as' => 'payment.cashDeposit.list', 'uses' => 'PaymentController@cashDepositList'
        ]);


        Route::get('report/cashDeposit/download', [
            'as' => 'payment.cashDeposit.list', 'uses' => 'PaymentController@cashDepositDownload'
        ]);

        Route::get('report/cashDeposit/excel', [
            'as' => 'payment.cashDeposit.list', 'uses' => 'PaymentController@cashDepositExcel'
        ]);

        Route::get('report/credit-note/list', [
            'as' => 'payment.credit_note.list', 'uses' => 'PaymentController@creditNoteList'
        ]);

        Route::get('report/credit-note/download', [
            'as' => 'payment.credit_note.list', 'uses' => 'PaymentController@creditNoteDownload'
        ]);

        Route::get('report/credit-note/excel', [
            'as' => 'payment.credit_note.list', 'uses' => 'PaymentController@creditNoteExcel'
        ]);
      /**
      * json Routes
      */
      Route::get('json/getInvoicesFor', [
        'as' => 'payment.json', 'uses' => 'PaymentController@getInvoicesFor'
      ]);

      Route::get('json/getOutlets', [
        'as' => 'payment.json', 'uses' => 'PaymentController@getOutlets'
      ]);

      Route::get('json/getRecipts', [
        'as' => 'recipt.json', 'uses' => 'PaymentController@getRecipts'
      ]);

      Route::post('json/addPayment', [
        'as' => 'payment.json', 'uses' => 'PaymentController@addPayment'
      ]);

        Route::get('json/bank/{id}', [
            'as' => 'payment.json', 'uses' => 'PaymentController@getBank'
        ]);

      
    });
});