<?php
use Application\Configuration\Http\Controllers\ConfigurationController;
use Application\WebService\Http\Controllers\GcmService;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web'])->group(function () {

    Route::prefix('configuration')->namespace('Application\Configuration\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [ConfigurationController::class, 'addView'])->name('configuration.add');
        Route::get('repActive/list', [ConfigurationController::class, 'listView'])->name('batch.list');
        Route::get('repActive/json/list', [ConfigurationController::class, 'jsonList'])->name('batch.list');
        
        // Uncomment the following routes when needed
        // Route::get('edit/{id}', [BatchPriceController::class, 'editView'])->name('batch.edit');
        // Route::get('listE/{id?}', 'GrnController@getParentList')->name('batch.edit');

        /**
         * POST Routes
         */
        // Uncomment the following routes when needed
        // Route::post('add', [BatchPriceController::class, 'add'])->name('batch.add');
        // Route::post('delete', 'GrnController@delete')->name('batch.delete');
        // Route::post('edit/{id}', [BatchPriceController::class, 'edit'])->name('batch.edit');

        Route::post('repActive/status', [ConfigurationController::class, 'status'])->name('batch.status');
        
        Route::get('/rep/list', [ConfigurationController::class, 'repListView'])->name('rep.list');
        Route::get('/rep/db', [ConfigurationController::class, 'getRepDB'])->name('rep.db');
    });

    Route::prefix('gcm')->namespace('Application\WebService\Http\Controllers')->group(function () {
        Route::post('send', [GcmService::class, 'sendCommands'])->name('index');
    });
});
