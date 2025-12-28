<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BusController;
use App\Http\Controllers\AdminController;
    
// User pages
Route::get('/', function () {
    return view('user.user');
});

Route::get('/track', [BusController::class, 'userTrack'])->name('user.track');
Route::get('/schedule', function () {
    return view('user.schedule');
});
Route::get('/bus/{id}', [BusController::class, 'show'])->name('bus.show');

// Admin pages
Route::get('/adminportal', function () {
    return view('user.adminportal');
});
Route::post('/adminportal', [AdminController::class, 'login'])->name('admin.login');
Route::post('/user/adminportal', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.admin');
Route::get('/admin/track', [AdminController::class, 'track'])->name('admin.track');
Route::get('/admin/schedule', [AdminController::class, 'schedule'])->name('admin.schedule');
Route::get('admin/businfo', [AdminController::class, 'busInfo'])->name('admin.businfo');
Route::get('/admin/report', [BusController::class, 'dailyReport'])->name('admin.report');

// Bus CRUD
Route::get('/businfo', [BusController::class, 'index'])->name('bus.index');
Route::post('/businfo', [BusController::class, 'store'])->name('bus.store');
Route::get('/businfo/{bus}/edit', [BusController::class, 'edit'])->name('bus.edit');
Route::put('/businfo/{bus}', [BusController::class, 'update'])->name('bus.update');
Route::delete('/businfo/{bus}', [BusController::class, 'destroy'])->name('bus.destroy');

Route::get('/live-bus-count', [BusController::class, 'liveBusCount']);

Route::get('/bus-location', function () {
    return \App\Models\Bus::select('id', 'plate_number', 'latitude', 'longitude')->get();

Route::get('/force-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migration Success: <br>' . nl2br(Artisan::output());
    } catch (\Exception $e) {
        return 'Migration Failed: ' . $e->getMessage();
    }
});

