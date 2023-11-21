<?php

use Application\TargetManage\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::group(['prefix' => 'marketeerTarget', 'namespace' => 'Application\TargetManage\Http\Controllers'], function () {

        /**
         * GET Routes
         */
        Route::get('add', [TargetController::class, 'addView'])->name('marketeer.target.add');
        
        Route::get('list', [TargetController::class, 'listView'])->name('marketeer.target.list');
        
        Route::get('json/list', [TargetController::class, 'jsonList'])->name('marketeer.target.json.list');
        
        Route::get('edit/{id}', [TargetController::class, 'editView'])->name('marketeer.target.edit');
        
        Route::get('filter', [TargetController::class, 'filter'])->name('marketeer.target.filter');

        /**
         * POST Routes
         */
        Route::post('add', [TargetController::class, 'add'])->name('marketeer.target.store');
        
        Route::post('delete', [TargetController::class, 'delete'])->name('marketeer.target.delete');
        
        Route::post('edit/{id}', [TargetController::class, 'edit'])->name('marketeer.target.update');
    });
});
