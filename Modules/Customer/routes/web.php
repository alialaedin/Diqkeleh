<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Admin\AddressController;
use Modules\Customer\Http\Controllers\Admin\CustomerController;
use Modules\Customer\Http\Controllers\Admin\CustomerSearchController;

Route::adminSuperGroup(function () {

	Route::prefix('/customers')->name('customers.')->group(function () {
		Route::get('/search', [CustomerSearchController::class, 'search'])->name('search');
		Route::get('/order-search', [CustomerSearchController::class, 'searchForOrder'])->name('order-search');
	});

	Route::resource('/customers', CustomerController::class);
	Route::resource('/addresses', AddressController::class)->except('show');
});
