<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AirQualityController;
use App\Http\Controllers\Admin\WebMasterAuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SensorController;


/*
|--------------------------------------------------------------------------
| Public User Routes
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
| Admin Logins
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', function () {
    return view('pages.auth.admin-login');
})->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('/admin/webmaster-login', function () {
    return view('pages.auth.webmaster-login');
})->name('webmaster.login');

Route::post('/admin/webmaster-login', [WebMasterAuthController::class, 'login'])->name('webmaster.login.submit');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.role.selection');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard + Features (for authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/aqi-status', function () {
        return view('pages.admin.full-aqi-status');
    })->name('admin.aqi.full');

    // ✅ Sensor Management
    
    Route::get('/admin/sensor-management', [SensorController::class, 'index'])->name('admin.sensor.management');
    Route::post('/admin/sensors', [SensorController::class, 'store'])->name('sensors.store');
    Route::delete('/admin/sensors/{id}', [SensorController::class, 'destroy'])->name('sensors.destroy');
    Route::put('/admin/sensors/{id}', [SensorController::class, 'update'])->name('sensors.update');

   

    // Alert + Data + User Management
    Route::get('/admin/alert-configuration', function () {
        return view('pages.admin.alerts');
    })->name('admin.alert');

    Route::get('/admin/data-management', function () {
        return view('pages.admin.data-management');
    })->name('admin.data.management');

    Route::get('/admin/user-management', function () {
        return view('pages.admin.user-management');
    })->middleware('auth')->name('admin.user.management');
});
