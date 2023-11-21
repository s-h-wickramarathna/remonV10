<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth','web'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('index');
    Route::get('/json/list', [WelcomeController::class, 'jsonList'])->name('index');
    Route::get('/json/listMonthly', [WelcomeController::class, 'monthlyData'])->name('index');
    Route::get('/json/listYearly', [WelcomeController::class, 'yearlyData'])->name('index');
    Route::get('/json/listweekly', [WelcomeController::class, 'weeklyData'])->name('index');
    Route::get('/json/listMonthlyTargetvsAchievement', [WelcomeController::class, 'monthlyTargetvsAchievement'])->name('index');
    Route::get('/json/listYearlyTargetvsAchievement', [WelcomeController::class, 'yearlyTargetvsAchievement'])->name('index');
    Route::get('/json/listWeeklyPreOrdervsAchievement', [WelcomeController::class, 'WelcomeController@weeklyPreOrdervsAchievement'])->name('index');
});

Route::get('user/login', [AuthController::class, 'loginView'])->name('login');
Route::post('user/login', [AuthController::class, 'login'])->name('user.login');
Route::get('user/logout', [AuthController::class, 'logout'])->name('user.logout');

Route::prefix('api')->group(function () {
    
    Route::get('auth', [WelcomeController::class, 'marketeerAuth'])->name('index');
    Route::get('customer-list', [WelcomeController::class, 'customerList'])->name('index');
    Route::get('dashboard-data', [WelcomeController::class, 'dashboardData'])->name('index');
    Route::get('customer-outstanding', [WelcomeController::class, 'getOutstandingData'])->name('index');
    Route::get('job-list', [WelcomeController::class, 'jobList'])->name('index');
    Route::get('job-data', [WelcomeController::class, 'getJobData'])->name('index');
    Route::get('customer-aging', [WelcomeController::class, 'getCustomerOutstanding'])->name('index');
    Route::get('check/payment', [WelcomeController::class, 'checkPayment'])->name('index');
    Route::get('reset/discount', [WelcomeController::class, 'resetDiscount'])->name('index');
    Route::get('reset/due', [WelcomeController::class, 'resetDue'])->name('index');
    Route::get('add-location', [WelcomeController::class, 'addLocation'])->name('index');
    Route::get('job-data', [WelcomeController::class, 'getJobData'])->name('index');

});

