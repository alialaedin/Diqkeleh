<?php

use Illuminate\Support\Facades\Route;
use Modules\Courier\Http\Controllers\Admin\CourierController;

Route::adminSuperGroup(function () {
	Route::resource('/couriers', CourierController::class);
});
