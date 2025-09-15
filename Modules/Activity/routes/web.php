<?php

use Illuminate\Support\Facades\Route;
use Modules\Activity\Http\Controllers\ActivityController;

Route::adminSuperGroup(function () {
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
});
