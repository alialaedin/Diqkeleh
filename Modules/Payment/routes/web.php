<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\Admin\PaymentController;

Route::adminSuperGroup(function () {
	Route::resource('/payments', PaymentController::class)->except('show');
});
