<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // =====================
    // Dashboard
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // =====================
    // Analisis
    // =====================
    Route::get('/analisis', [AnalisisController::class, 'index'])
        ->name('analisis');

    // =====================
    // Prediksi
    // =====================
    Route::get('/prediksi', [PrediksiController::class, 'index'])
        ->name('prediksi');

    Route::post('/prediksi', [PrediksiController::class, 'hitung'])
        ->name('prediksi.hitung');

    // =====================
    // Laporan
    // =====================
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::post('/laporan/import', [LaporanController::class, 'import'])
        ->name('laporan.import');

    Route::post('/laporan/import-detail', [LaporanController::class, 'importDetail'])
        ->name('laporan.import.detail');

    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])
        ->name('laporan.excel');

    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.pdf');

    Route::post('/laporan/reset-detail', [LaporanController::class, 'resetDetail'])
        ->name('laporan.reset.detail');
});

require __DIR__ . '/auth.php';
