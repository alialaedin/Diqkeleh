<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Admin\CategoryController;

Route::adminSuperGroup(function () {
	Route::resource('/categories', CategoryController::class);

	Route::prefix('/sort')->name('categories.')->group(function () {
		Route::put('/', [CategoryController::class, 'sort'])->name('sort');
		Route::put('/{category}', [CategoryController::class, 'sortProducts'])->name('sort-products');
	});

});
