<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AirQualityController;
use App\Http\Controllers\Admin\WebMasterAuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SensorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SimulationController;
use App\Http\Controllers\Admin\AlertRuleController;
use App\Http\Controllers\Admin\DashboardController;



/*
|--------------------------------------------------------------------------
| Public User Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('pages.user.home');
})->name('home');

Route::get('/air-quality', [AirQualityController::class, 'airQualityPage'])->name('airquality');
Route::get('/api/sensors', [AirQualityController::class, 'getSensors'])->name('api.sensors');
Route::get('/air-quality/data/{id}', [AirQualityController::class, 'getSensorData'])->name('airquality.sensor.data');




// User side - Historical Data page
Route::get('/historical-data', [AirQualityController::class, 'historicalData'])->name('historical.data');

// API endpoint to fetch historical sensor readings
Route::get('/api/historical-data', [AirQualityController::class, 'fetchHistoricalData'])->name('api.historical.data');



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

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    // ✅ Sensor Management
    Route::get('/admin/sensor-management', [SensorController::class, 'index'])->name('admin.sensor.management');
    Route::post('/admin/sensors', [SensorController::class, 'store'])->name('sensors.store');
    Route::delete('/admin/sensors/{id}', [SensorController::class, 'destroy'])->name('sensors.destroy');
    Route::put('/admin/sensors/{id}', [SensorController::class, 'update'])->name('sensors.update');
    Route::post('/admin/sensors/update-aqi/{id}', [SensorController::class, 'updateAqi'])->name('sensors.update.aqi');
    Route::get('/admin/sensor-history/{sensorId}', [SensorController::class, 'sensorHistory']);



   
    // Alert - Configuration
    Route::get('/admin/alert-configuration', [AlertRuleController::class, 'index'])->name('admin.alert');
    Route::post('/admin/alerts', [AlertRuleController::class, 'store'])->name('alerts.store');
    Route::delete('/admin/alerts/{id}', [AlertRuleController::class, 'destroy'])->name('alerts.destroy');
    Route::post('/admin/alerts/mark-seen', [AlertRuleController::class, 'markSeen'])->name('alerts.markSeen');

    
    // ✅Data simulation Management
    Route::get('/admin/data-management', [SimulationController::class, 'index'])->name('admin.data.management');
    Route::post('/admin/simulation-settings', [SimulationController::class, 'store'])->name('simulation.settings.store');
    Route::get('/admin/alerts/{id}', [AlertRuleController::class, 'show'])->name('alerts.show');
    Route::put('/admin/alerts/{id}', [AlertRuleController::class, 'update'])->name('alerts.update');
    Route::post('/admin/simulation-toggle', [SimulationController::class, 'toggleStatus'])->name('simulation.settings.toggle');
    
    // User Management
    Route::get('/admin/user-management', [UserController::class, 'index'])->name('admin.user.management');
    Route::post('/admin/user-management', [UserController::class, 'store'])->name('admin.user.store');
    Route::put('/admin/user-management/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user-management/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');
    
    // ✅ Load AQI readings for the frontend table
    Route::get('/admin/data-readings', function () {
        return \App\Models\SensorReading::latest()->take(20)->get(); // Fetch last 20 readings
    });


});
