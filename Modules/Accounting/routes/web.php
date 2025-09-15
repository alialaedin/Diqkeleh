<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\Admin\ExpenseController;
use Modules\Accounting\Http\Controllers\Admin\HeadlineController;
use Modules\Accounting\Http\Controllers\Admin\RevenueController;

Route::adminSuperGroup(function () {
	Route::resource('/headlines', HeadlineController::class)->except('show');
	Route::resource('/revenues', RevenueController::class)->except('show');
	Route::resource('/expenses', ExpenseController::class)->except('show');
});