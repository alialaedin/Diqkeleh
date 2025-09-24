<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

Route::adminSuperGroup(function () {
	Route::prefix('/reports')->name('reports.')->group(function () {
		Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
		Route::get('/products', [ReportController::class, 'products'])->name('products');
		Route::get('/today-sales', [ReportController::class, 'todaySales'])->name('today-sales');
	});
});
