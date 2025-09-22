<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Admin\CategoryController;

Route::adminSuperGroup(function () {
	Route::resource('/categories', CategoryController::class)->except('show');
	Route::put('/sort', [CategoryController::class, 'sort'])->name('categories.sort');
});
