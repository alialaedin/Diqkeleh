<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::middleware('auth:admin')->group(function () {
	Route::prefix('/admin')->group(function () {
		Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
		Route::redirect('/', '/admin/dashboard');
	});
});
