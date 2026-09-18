<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $totalIdpel = \App\Models\Idpel::count();
    $meterisasiCount = \App\Models\Idpel::where('status', 'meterisasi')->count();
    $abonemenCount = \App\Models\Idpel::where('status', 'abonemen')->count();
    $totalLamps = \App\Models\Lamp::count();
    
    // Group lamps by type
    $lampsByType = \App\Models\Lamp::selectRaw('type, count(*) as count')
        ->groupBy('type')
        ->get()
        ->keyBy('type');
        
    // Calculate total efficiencies
    // This is a simplified calculation matching the plan (18.85B -> 11.01B)
    $yearlySavings = 7.84 * 1000000000;
    // Fetch paginated idpels with relations
    $idpels = \App\Models\Idpel::with(['district', 'substations'])->paginate(5);

    return Inertia::render('Dashboard', [
        'kpi' => [
            'total_idpel' => $totalIdpel,
            'meterisasi_count' => $meterisasiCount,
            'abonemen_count' => $abonemenCount,
            'meterisasi_percentage' => $totalIdpel > 0 ? round(($meterisasiCount / $totalIdpel) * 100, 1) : 0,
            'abonemen_percentage' => $totalIdpel > 0 ? round(($abonemenCount / $totalIdpel) * 100, 1) : 0,
            'total_lamps' => $totalLamps,
            'smart_led_count' => $lampsByType['Smart LED']->count ?? 0,
            'led_konvensional_count' => $lampsByType['LED Konvensional']->count ?? 0,
            'son_t_count' => $lampsByType['Son-T']->count ?? 0,
            'yearly_savings' => $yearlySavings,
        ],
        'idpels' => $idpels,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/dashboard/spreadsheet', [\App\Http\Controllers\SpreadsheetDashboardController::class, 'index'])->name('dashboard.spreadsheet');
    Route::post('/dashboard/spreadsheet/clear-cache', [\App\Http\Controllers\SpreadsheetDashboardController::class, 'clearCache'])->name('dashboard.spreadsheet.clear-cache');

    Route::resource('idpels', \App\Http\Controllers\IdpelController::class);
    Route::get('/maps', [\App\Http\Controllers\MapController::class, 'index'])->name('maps.index');
});

require __DIR__.'/auth.php';
