<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;
use Modules\Store\Http\Controllers\Admin\StoreMultiChargeController;
use Modules\Store\Http\Controllers\Admin\StoreTransactionController;

Route::adminSuperGroup(function () {

	Route::prefix('/stores')->name('stores.')->group(function () {
		Route::get('/', [StoreController::class, 'index'])->name('index');
		Route::put('/', [StoreController::class, 'store'])->name('store');
	});

	Route::prefix('/store-multi-charge')->name('store-multi-charge.')->group(function () {
		Route::get('/', [StoreMultiChargeController::class, 'index'])->name('index');
		Route::patch('/', [StoreMultiChargeController::class, 'update'])->name('update');
	});

	Route::get('/store-transactions', [StoreTransactionController::class, 'index'])->name('store-transactions.index');
});
