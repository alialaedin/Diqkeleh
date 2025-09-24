<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderController;
use Modules\Order\Http\Controllers\Admin\OrderItemController;

Route::adminSuperGroup(function () {

	Route::prefix('/orders')->name('orders.')->group(function () {
		Route::get('/today', [OrderController::class, 'today'])->name('today-orders');
		Route::get('{order}/print', [OrderController::class, 'print'])->name('print');
		Route::patch('/{order}/change-status', [OrderController::class, 'changeStatus'])->name('change-status');
		Route::post('/pay', [OrderController::class, 'pay'])->name('pay');
	});

	Route::resource('/orders', OrderController::class)->only(['index', 'create', 'show', 'store', 'update']);

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
