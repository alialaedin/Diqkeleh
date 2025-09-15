<?php

use Illuminate\Support\Facades\Route;
use Modules\Unit\Http\Controllers\Admin\UnitController;

Route::adminSuperGroup(function () {
	Route::resource('/units', UnitController::class)->except('show');
});
