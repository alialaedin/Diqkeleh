<?php

use Illuminate\Support\Facades\Route;
use Modules\Courier\Http\Controllers\Admin\CourierController;
use Modules\Courier\Http\Controllers\Admin\SettlementController;

Route::adminSuperGroup(function () {
	Route::resource('/couriers', CourierController::class);

	Route::prefix('/settlement')->name('settlement.')->group(function () {
		Route::get('/', [SettlementController::class, 'index'])->name('index');
		Route::patch('/', [SettlementController::class, 'update'])->name('update');
	});
});
