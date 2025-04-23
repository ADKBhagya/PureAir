<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.user.home');
})->name('home');

Route::get('/air-quality', function () {
    return view('pages.user.airquality');
})->name('airquality');

Route::get('/admin/data-management', function () {
    return view('pages.admin.data-management');
});


use App\Http\Controllers\User\AirQualityController;

Route::get('/historical-data', [AirQualityController::class, 'historicalData'])->name('historical.data');

Route::get('/admin/data-management', function () {
    return view('pages.admin.data-management');
})->name('admin.data.management');

