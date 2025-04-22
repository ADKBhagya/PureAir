<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AirQualityController;
use App\Http\Controllers\Admin\WebMasterAuthController;
use App\Http\Controllers\Admin\AdminAuthController;


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
    return view('pages.auth.admin-login');
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| WebMaster Login Page & Submit
|--------------------------------------------------------------------------
*/

Route::get('/admin/webmaster-login', function () {
    return view('pages.auth.webmaster-login');
})->name('webmaster.login');

Route::post('/admin/webmaster-login', [WebMasterAuthController::class, 'login'])->name('webmaster.login.submit');
/*
|--------------------------------------------------------------------------
| Dummy Page:  Data Management
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| Dummy Page: Dashboard
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| aqi status
|--------------------------------------------------------------------------
*/
Route::get('/admin/aqi-status', function () {
    return view('pages.admin.full-aqi-status');
})->name('admin.aqi.full');

/*
|--------------------------------------------------------------------------
| Dummy Page: Admin user management
|--------------------------------------------------------------------------
*/
Route::get('/admin/user-management', function () {
    return view('pages.admin.user-management');
})->name('admin.user.management');

/*
|--------------------------------------------------------------------------
| Dummy Page: Sensor management
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Dummy Page: Alert Configuration
|--------------------------------------------------------------------------
*/
