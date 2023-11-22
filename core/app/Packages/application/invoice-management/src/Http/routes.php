<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function()
{
    Route::group(['prefix' => 'invoice', 'namespace' => 'Application\InvoiceManage\Http\Controllers'], function(){
        /**
         * GET Routes
         */
        Route::get('add/{id}', [
          'as' => 'invoice.add', 'uses' => 'InvoiceController@addView'
        ]);

        Route::get('admin_authentication', [
          'as' => 'invoice.add', 'uses' => 'InvoiceController@adminAuthentication'
        ]);

        Route::get('print', [
            'as' => 'invoice.print', 'uses' => 'InvoiceController@toPrint'
        ]);


        Route::get('toPrint', [
           'as' => 'invoice.add', 'uses' => 'InvoiceController@toPrint'
        ]);

        Route::get('list', [
          'uses' => 'InvoiceController@invoiceList',
          'as'   => 'invoice.list'
        ]);

        Route::get('customerlist', [
            'uses' => 'InvoiceController@listOutlet',
            'as'   => 'invoice.list'
        ]);

        Route::get('search', [
            'as' => 'invoice.list', 'uses' => 'InvoiceController@search'
        ]);

        Route::get('add/getData/{id}', [
            'as' => 'invoice.add', 'uses' => 'InvoiceController@getData'
        ]);

        Route::get('pending-approve', [
                'as' => 'invoice.admin', 'uses' => 'InvoiceController@pending_approve'
        ]);

        Route::get('approve', [
            'as' => 'invoice.admin', 'uses' => 'InvoiceController@approve'
        ]);

        Route::get('get/credit/{id}', [
            'as' => 'invoice.credit', 'uses' => 'InvoiceController@getCreditData'
        ]);

        Route::get('reject', [
            'as' => 'invoice.admin', 'uses' => 'InvoiceController@reject'
        ]);
		
    	

        /**
         * JSON Routes
         */
        Route::get('json/getOutlets', [
            'as' => 'invoice.add',
            'uses' => 'InvoiceController@getOutlets'
        ]);
      
        /*
         * get data of filtered value in data table
         */
        Route::get('json/getInvoices', [
            'as' => 'invoice.list',
            'uses' => 'InvoiceController@getInvoices'
        ]);

        Route::get('getProductByBrand', [
          'as' => 'invoice.add', 'uses' => 'InvoiceController@getProductByBrand'
        ]);

        Route::get('getProductByCategory', [
            'as' => 'invoice.add', 'uses' => 'InvoiceController@getProductByCategory'
        ]);

        Route::get('getProductByRange', [
            'as' => 'invoice.add', 'uses' => 'InvoiceController@getProductByRange'
        ]);

        Route::get('getMarketeer', [
            'as' => 'invoice.add', 'uses' => 'InvoiceController@getMarketeer'
        ]);

        Route::get('aging/download', [
            'as' => 'invoice.list', 'uses' => 'InvoiceController@agingDownload'
        ]);

        Route::get('aging/excel', [
            'as' => 'invoice.list', 'uses' => 'InvoiceController@agingExcel'
        ]);

        Route::get('payment-aging', [
            'as' => 'invoice.payment_aging', 'uses' => 'InvoiceController@payment_aging'
        ]);

        Route::get('payment-aging/excel', [
            'as' => 'invoice.payment_aging', 'uses' => 'InvoiceController@paymentAgingExcel'
        ]);

        /**
        * POST Routes
        */
        Route::post('credit/add', [
          'as' => 'invoice.credit', 'uses' => 'InvoiceController@addCreditNote'
        ]);

        Route::post('add', [
          'as' => 'invoice.add', 'uses' => 'InvoiceController@add'
        ]);

        Route::post('amend/add', [
          'as' => 'invoice.add', 'uses' => 'InvoiceController@addAmendInvoice'
        ]);

        Route::post('new/add', [
            'as' => 'invoice.add', 'uses' => 'InvoiceController@addNewInvoice'
        ]);

        Route::post('delete', [
            'as' => 'invoice.delete', 'uses' => 'InvoiceController@delete'
        ]);

       
    });
});