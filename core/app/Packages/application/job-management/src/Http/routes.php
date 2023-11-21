<?php
use Illuminate\Support\Facades\Route;
use Application\JobManage\Http\Controllers\JobController;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('job')->namespace('Application\JobManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('index', [JobController::class, 'index'])->name('job.index');
        Route::get('data/add/{id?}', [JobController::class, 'dataAdd'])->name('job.data.add');
        Route::get('data/edit/{id?}', [JobController::class, 'dataEdit'])->name('job.data.edit');
        Route::get('data/search', [JobController::class, 'dataSearch'])->name('job.list');
        Route::get('new/add', [JobController::class, 'newAddView'])->name('job.add');
        Route::get('new/getData/{id}', [JobController::class, 'getData'])->name('job.getData');
        Route::get('qr/list/{id}', [JobController::class, 'qrList'])->name('job.qrList');
        Route::get('qr/print/{id}', [JobController::class, 'qrPrint'])->name('job.qrPrint');
        Route::get('new/list', [JobController::class, 'newList'])->name('job.newList');
        Route::get('report', [JobController::class, 'reportView'])->name('job.list');
        Route::get('new/edit/{id}', [JobController::class, 'editNewJob'])->name('job.change');
        Route::get('resize', [JobController::class, 'resize'])->name('user.role.add');
        Route::get('list', [JobController::class, 'listView'])->name('job.list');
        Route::get('pending-report', [JobController::class, 'jobReport'])->name('job.list');
        Route::get('pending-report/print', [JobController::class, 'jobReportPrint'])->name('job.list');
        Route::get('report/no-job-customers', [JobController::class, 'noJobCustomers'])->name('job.list');
        Route::get('report/no-job-customers/print', [JobController::class, 'noJobCustomersPrint'])->name('job.list');
        Route::get('search', [JobController::class, 'search'])->name('job.list');
        Route::get('processing/{id}', [JobController::class, 'processing'])->name('job.change');
        Route::get('done/{id}', [JobController::class, 'done'])->name('job.change');
        Route::get('delivered/{id}', [JobController::class, 'delivered'])->name('job.change');
        Route::get('confirm/{id}', [JobController::class, 'confirm'])->name('job.confirm');
        Route::get('add', [JobController::class, 'addView'])->name('job.add');
        Route::get('getData/{id}', [JobController::class, 'getData'])->name('job.add');
        Route::get('print/{id}', [JobController::class, 'toPrint'])->name('job.list');
        Route::get('level/{id}', [JobController::class, 'levelView'])->name('job.level');
        Route::get('level/getData/{id}', [JobController::class, 'getLevelData'])->name('job.level');
        Route::get('edit/{id}', [JobController::class, 'editJob'])->name('job.change');
        Route::get('planing/edit/{qr_id}', [JobController::class, 'editPlaningJob'])->name('job.change');
        Route::get('edit/getData/{qr_id}', [JobController::class, 'getData'])->name('job.add');
        Route::get('other/edit/{qr_id}', [JobController::class, 'editOtherJob'])->name('job.change');
        Route::get('new/search/{id?}', [JobController::class, 'newJobSearch'])->name('job.list');
        Route::get('qa/edit/{id}', [JobController::class, 'editQAJob'])->name('job.change');
        Route::get('download', [JobController::class, 'download'])->name('job.list');

        /*
         * get data of filtered value in data table
         */
        Route::get('filter', [TargetController::class, 'filter'])->name('marketeer.target.list');

        Route::post('add', [JobController::class, 'add'])->name('job.add');
        Route::post('level/{id}', [JobController::class, 'editLevel'])->name('job.level');
        Route::post('edit/{id}', [JobController::class, 'edit'])->name('job.change');
        Route::post('data/add/{id?}', [JobController::class, 'addData'])->name('job.data.add');
        Route::post('data/edit/{id?}', [JobController::class, 'editData'])->name('job.data.edit');
        Route::post('data/delete', [JobController::class, 'deleteData'])->name('job.data.edit');
        Route::post('new/add', [JobController::class, 'AddNewJob'])->name('job.add');
        Route::post('new/edit/{qr_id}', [JobController::class, 'newJobEdit'])->name('job.change');
        Route::post('planing/edit/{qr_id}', [JobController::class, 'newPlaningEdit'])->name('job.change');
        Route::post('qa/edit/{qr_id}', [JobController::class, 'newQAEdit'])->name('job.change');
    });
});
