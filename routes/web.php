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


