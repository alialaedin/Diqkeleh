<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

Route::adminSuperGroup(function () {
    Route::resource('/admins', AdminController::class);
});
