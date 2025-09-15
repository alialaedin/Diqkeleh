<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\Admin\AccountController;
use Modules\Employee\Http\Controllers\Admin\EmployeeController;
use Modules\Employee\Http\Controllers\Admin\SalaryController;

Route::adminSuperGroup(function () {
	Route::resource('/employees', EmployeeController::class);
	Route::resource('/salaries', SalaryController::class);
	Route::resource('/accounts', AccountController::class)->except('show');
});
