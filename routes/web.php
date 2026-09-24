<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return app(AuthenticatedSessionController::class)->create();
})->name('home');

Route::get('/dashboard', [\App\Http\Controllers\SpreadsheetDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard/detail/{id}', [\App\Http\Controllers\SpreadsheetDashboardController::class, 'show'])->name('dashboard.show');
    Route::post('/dashboard/clear-cache', [\App\Http\Controllers\SpreadsheetDashboardController::class, 'clearCache'])->name('dashboard.clear-cache');

    Route::resource('idpels', \App\Http\Controllers\IdpelController::class);
    Route::get('/maps', [\App\Http\Controllers\MapController::class, 'index'])->name('maps.index');
});

require __DIR__.'/auth.php';
