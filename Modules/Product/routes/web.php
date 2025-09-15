<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\ProductController;

Route::adminSuperGroup(function () {
	Route::resource('/products', ProductController::class);
});