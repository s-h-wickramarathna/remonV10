<?php
use Application\Discount\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

/**
 * PERMISSIONS MANAGEMENT ROUTES
 */
Route::middleware(['auth', 'web'])->group(function () {
    Route::prefix('discount/group')->namespace('Application\Discount\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [DiscountController::class, 'addGroupView'])->name('discountGroup.add');
        Route::get('json/getProducts', [DiscountController::class, 'getProducts'])->name('discountGroup.json');
        Route::get('list', [DiscountController::class, 'listViewDiscountGroup'])->name('discountGroup.list');
        Route::get('json/list', [DiscountController::class, 'jsonListGroupView'])->name('discountGroup.list');
        Route::get('json/listDetailGroup', [DiscountController::class, 'jsonListGroupDetailView'])->name('discountGroup.listDetailGroup');
        Route::get('json/getRuleType', [DiscountController::class, 'getRuleType'])->name('discountGroup.json');

        /**
         * POST Routes
         */
        Route::post('add', [DiscountController::class, 'addGroup'])->name('discountGroup.add');
        Route::post('status', [DiscountController::class, 'statusGroup'])->name('discountGroup.status');
    });

    Route::prefix('discount/rule')->namespace('Application\Discount\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [DiscountController::class, 'addRuleView'])->name('discountRule.add');
        Route::get('json/listDetailRule', [DiscountController::class, 'jsonListRuleDetailView'])->name('discountRule.listDetailRule');
        Route::get('list', [DiscountController::class, 'listViewDiscountRule'])->name('discountRule.list');
        Route::get('json/list', [DiscountController::class, 'jsonListRuleView'])->name('discountRule.list');

        /**
         * POST Routes
         */
        Route::post('add', [DiscountController::class, 'addRule'])->name('discountRule.add');
        Route::post('status', [DiscountController::class, 'statusRule'])->name('discountRule.status');
    });

    Route::prefix('discount/outletAssign')->namespace('Application\Discount\Http\Controllers')->group(function () {
        /**
         * GET Routes
         */
        Route::get('add', [DiscountController::class, 'addGroupOutletView'])->name('discounTotOutlet.add');
        Route::get('json/getLocation', [DiscountController::class, 'getLocation'])->name('discounTotOutlet.json');
        Route::get('list', [DiscountController::class, 'listGroupOutletView'])->name('discounTotOutlet.list');
        Route::get('json/list', [DiscountController::class, 'listGroupOutlet'])->name('discounTotOutlet.list');
        Route::get('json/listDetailGroupOutlet', [DiscountController::class, 'jsonListOutletGroupDetailView'])->name('discounTotOutlet.listDetailGroupOutlet');

        /**
         * POST Routes
         */
        Route::post('add', [DiscountController::class, 'addDiscountGroupOutlet'])->name('discounTotOutlet.add');
        Route::post('status', [DiscountController::class, 'statusGroupOutlet'])->name('discounTotOutlet.status');
    });
});
