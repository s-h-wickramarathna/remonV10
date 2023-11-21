<?php
use Illuminate\Support\Facades\Route;
use Application\EmployeeManage\Http\Controllers\EmployeeTypeController;
use Application\EmployeeManage\Http\Controllers\EmployeeController;

Route::middleware(['auth', 'web'])->group(function () {

    // Employee Type Routes
    Route::prefix('employee/type')->namespace('Application\EmployeeManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [EmployeeTypeController::class, 'addView'])->name('employee.type.add');
        Route::get('list', [EmployeeTypeController::class, 'listView'])->name('employee.type.list');
        Route::get('json/list', [EmployeeTypeController::class, 'jsonList'])->name('employee.type.list');
        Route::get('edit/{id}', [EmployeeTypeController::class, 'editView'])->name('employee.type.edit');

        /**
         * POST Routes
         */
        Route::post('add', [EmployeeTypeController::class, 'add'])->name('employee.type.add');
        Route::post('delete', [EmployeeTypeController::class, 'delete'])->name('employee.type.delete');
        Route::post('edit/{id}', [EmployeeTypeController::class, 'edit'])->name('employee.type.edit');
    });

    // Employee Routes
    Route::prefix('employee')->namespace('Application\EmployeeManage\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [EmployeeController::class, 'addView'])->name('employee.add');
        Route::get('list', [EmployeeController::class, 'listView'])->name('employee.list');
        Route::get('json/list', [EmployeeController::class, 'jsonList'])->name('employee.list');
        Route::get('edit/{id}', [EmployeeController::class, 'editView'])->name('employee.edit');
        Route::get('barcode/{id}', [EmployeeController::class, 'genBarCode'])->name('employee.barcode');
        Route::get('listE/{id?}', [EmployeeController::class, 'getParentList'])->name('employee.listE');
        Route::get('filter', [EmployeeController::class, 'filter'])->name('employee.filter');
        Route::get('view', [EmployeeController::class, 'getViewData'])->name('employee.view');
        Route::get('getParent', [EmployeeController::class, 'getParent'])->name('employee.getParent');

        /**
         * POST Routes
         */
        Route::post('add', [EmployeeController::class, 'add'])->name('employee.add');
        Route::post('delete', [EmployeeController::class, 'delete'])->name('employee.delete');
        Route::post('edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::post('status', [EmployeeController::class, 'status'])->name('employee.status');
    });
});
