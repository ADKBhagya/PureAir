<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.user.home');
})->name('home');

Route::get('/air-quality', function () {
    return view('pages.user.airquality');
})->name('airquality');

use App\Http\Controllers\User\AirQualityController;

Route::get('/historical-data', [AirQualityController::class, 'historicalData'])->name('historical.data');

Route::get('/admin/role-selection', function () {
    return view('pages.auth.admin-selection');
})->name('admin.role.selection');

Route::get('/admin/login', function () {
    return 'Admin login placeholder';
})->name('admin.login');




