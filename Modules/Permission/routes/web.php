<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\Http\Controllers\Admin\RoleController;

Route::adminSuperGroup(function () {
	Route::resource('/roles', RoleController::class);
});
