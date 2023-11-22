<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth','web']], function () {
    Route::group(['prefix' => 'job', 'namespace' => 'Application\JobManage\Http\Controllers'], function () {
        /**
         * GET Routes
         */

        Route::get('index', [
            'as' => 'job.index', 'uses' => 'JobController@index'
        ]);

        Route::get('data/add/{id?}', [
            'as' => 'job.data.add', 'uses' => 'JobController@dataAdd'
        ]);

        Route::get('data/edit/{id?}', [
            'as' => 'job.data.edit', 'uses' => 'JobController@dataEdit'
        ]);

        Route::get('data/search', [
            'as' => 'job.list', 'uses' => 'JobController@dataSearch'
        ]);

        Route::get('new/add', [
            'as' => 'job.add', 'uses' => 'JobController@newAddView'
        ]);

        Route::get('new/getData/{id}', [
            'as' => 'job.add', 'uses' => 'JobController@getData'
        ]);

        Route::get('qr/list/{id}', [
            'as' => 'job.list', 'uses' => 'JobController@qrList'
        ]);

        Route::get('qr/print/{id}', [
            'as' => 'job.print', 'uses' => 'JobController@qrPrint'
        ]);

        Route::get('new/list', [
            'as' => 'job.list', 'uses' => 'JobController@newList'
        ]);

        Route::get('report', [
            'as' => 'job.list', 'uses' => 'JobController@reportView'
        ]);

        Route::get('new/edit/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@editNewJob'
        ]);

        Route::get('resize', [
            'as' => 'user.role.add', 'uses' => 'JobController@resize'
        ]);

        Route::get('list', [
            'as' => 'job.list', 'uses' => 'JobController@listView'
        ]);

        Route::get('pending-report', [
            'as' => 'job.list', 'uses' => 'JobController@jobReport'
        ]);

        Route::get('pending-report/print', [
            'as' => 'job.list', 'uses' => 'JobController@jobReportPrint'
        ]);

        Route::get('report/no-job-customers', [
            'as' => 'job.list', 'uses' => 'JobController@noJobCustomers'
        ]);

        Route::get('report/no-job-customers/print', [
            'as' => 'job.list', 'uses' => 'JobController@noJobCustomersPrint'
        ]);



        Route::get('search', [
            'as' => 'job.list', 'uses' => 'JobController@search'
        ]);

        Route::get('processing/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@processing'
        ]);

        Route::get('done/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@done'
        ]);

        Route::get('delivered/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@delivered'
        ]);

        Route::get('confirm/{id}', [
            'as' => 'job.confirm', 'uses' => 'JobController@confirm'
        ]);

        Route::get('add', [
            'as' => 'job.add', 'uses' => 'JobController@addView'
        ]);

        Route::get('getData/{id}', [
            'as' => 'job.add', 'uses' => 'JobController@getData'
        ]);

        Route::get('print/{id}', [
            'as' => 'job.list', 'uses' => 'JobController@toPrint'
        ]);

        Route::get('level/{id}', [
            'as' => 'job.level', 'uses' => 'JobController@levelView'
        ]);

        Route::get('level/getData/{id}', [
            'as' => 'job.level', 'uses' => 'JobController@getLevelData'
        ]);

        Route::get('edit/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@editJob'
        ]);

        Route::get('planing/edit/{qr_id}', [
            'as' => 'job.change', 'uses' => 'JobController@editPlaningJob'
        ]);

        Route::get('edit/getData/{qr_id}', [
            'as' => 'job.add', 'uses' => 'JobController@getData'
        ]);

        Route::get('other/edit/{qr_id}', [
            'as' => 'job.change', 'uses' => 'JobController@editOtherJob'
        ]);

        Route::get('new/search/{id?}', [
            'as' => 'job.list', 'uses' => 'JobController@newJobSearch'
        ]);

        Route::get('qa/edit/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@editQAJob'
        ]);

        Route::get('download', [
            'as' => 'job.list', 'uses' => 'JobController@download'
        ]);

        /*
         * get data of filtered value in data table
         */
        Route::get('filter', [
            'as' => 'marketeer.target.list', 'uses' => 'TargetController@filter'
        ]);

        Route::post('add', [
            'as' => 'job.add', 'uses' => 'JobController@add'
        ]);

        Route::post('level/{id}', [
            'as' => 'job.level', 'uses' => 'JobController@editLevel'
        ]);

        Route::post('edit/{id}', [
            'as' => 'job.change', 'uses' => 'JobController@edit'
        ]);

        Route::post('data/add/{id?}', [
            'as' => 'job.data.add', 'uses' => 'JobController@addData'
        ]);

        Route::post('data/edit/{id?}', [
            'as' => 'job.data.edit', 'uses' => 'JobController@editData'
        ]);
        Route::post('data/delete', [
            'as' => 'job.data.edit', 'uses' => 'JobController@deleteData'
        ]);

        Route::post('new/add', [
            'as' => 'job.add', 'uses' => 'JobController@AddNewJob'
        ]);

        Route::post('new/edit/{qr_id}', [
            'as' => 'job.change', 'uses' => 'JobController@newJobEdit'
        ]);

        Route::post('planing/edit/{qr_id}', [
            'as' => 'job.change', 'uses' => 'JobController@newPlaningEdit'
        ]);

        Route::post('qa/edit/{qr_id}', [
            'as' => 'job.change', 'uses' => 'JobController@newQAEdit'
        ]);

    });
});