<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Your RFID Route
Route::post('/update-count', [BusController::class, 'handleRfidScan']);

// GPS Tracker Route (This is what your ESP32 calls)
Route::post('/update-location', [BusController::class, 'updateLocation']);

// The Website (Leaflet Map) reads data from here
Route::get('/bus-location', [BusController::class, 'getLocations']);
