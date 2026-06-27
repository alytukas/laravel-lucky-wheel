<?php

use Illuminate\Support\Facades\Route;
use Gwebas\LuckyWheel\Http\Controllers\LuckyWheelController;
use Gwebas\LuckyWheel\Http\Controllers\Admin\LuckyWheelAdminController;

Route::group(['middleware' => ['web'], 'prefix' => 'lucky-wheel'], function () {
    Route::post('/spin', [LuckyWheelController::class, 'spin'])->name('lucky-wheel.spin');
    Route::post('/claim', [LuckyWheelController::class, 'claim'])->name('lucky-wheel.claim');
});

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admin/lucky-wheel', 'as' => 'admin.lucky-wheel.'], function () {
    Route::get('/', [LuckyWheelAdminController::class, 'index'])->name('index');
    Route::post('/settings', [LuckyWheelAdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/prizes', [LuckyWheelAdminController::class, 'storePrize'])->name('prizes.store');
    Route::delete('/prizes/{prize}', [LuckyWheelAdminController::class, 'destroyPrize'])->name('prizes.destroy');
});
