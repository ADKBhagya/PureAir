<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AirQualityController;
use App\Http\Controllers\Admin\AdministratorAuthController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.user.home');
})->name('home');

Route::get('/air-quality', function () {
    return view('pages.user.airquality');
})->name('airquality');

Route::get('/historical-data', [AirQualityController::class, 'historicalData'])->name('historical.data');

/*
|--------------------------------------------------------------------------
| Admin Role Selection
|--------------------------------------------------------------------------
*/

Route::get('/admin/role-selection', function () {
    return view('pages.auth.admin-selection');
})->name('admin.role.selection');

/*
|--------------------------------------------------------------------------
| Admin Login Placeholder (for monitoring Admin role)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return 'Admin login placeholder';
})->name('admin.login');

/*
|--------------------------------------------------------------------------
| Administrator Login Page & Submit
|--------------------------------------------------------------------------
*/

Route::get('/admin/administrator-login', function () {
    return view('pages.auth.administrator-login');
})->name('administrator.login');

Route::post('/admin/administrator-login', [AdministratorAuthController::class, 'login'])
    ->name('administrator.login.submit');
