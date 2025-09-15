<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderController;
use Modules\Order\Http\Controllers\Admin\OrderItemController;

Route::adminSuperGroup(function () {

	Route::prefix('/orders')->name('orders.')->group(function () {
		Route::get('{order}/print', [OrderController::class, 'print'])->name('print');
		Route::get('/', [OrderController::class, 'index'])->name('index');
		Route::get('/create', [OrderController::class, 'create'])->name('create');
		Route::get('{order}', [OrderController::class, 'show'])->name('show');
		Route::post('/', [OrderController::class, 'store'])->name('store');
		Route::patch('/{order}', [OrderController::class, 'update'])->name('update');
		Route::patch('/{order}/change-status', [OrderController::class, 'changeStatus'])->name('change-status');
	});

	Route::prefix('/order-items')->name('order-items.')->group(function () {

		Route::post(
			'/{order}/add-item', 
			[OrderItemController::class, 'addItem']
		)->name('add-item');

		Route::put(
			'/{orderItem}/update-quantity', 
			[OrderItemController::class, 'updateQuantity']
		)->name('update-quantity');

		Route::put(
			'/{orderItem}/change-status', 
			[OrderItemController::class, 'changeStatus']
		)->name('change-status');

	});
});
