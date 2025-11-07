<?php

use App\Http\Controllers\{ElevatorController, RevisionController, PublicController, PdfController};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('elevators.index')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    Route::get('/ascensores', [ElevatorController::class, 'index'])->name('elevators.index');
    Route::post('/ascensores', [ElevatorController::class, 'store'])->name('elevators.store');
    Route::get('/ascensores/{elevator}', [ElevatorController::class, 'show'])->name('elevators.show');
    Route::put('/ascensores/{elevator}', [ElevatorController::class, 'update'])->name('elevators.update');
    Route::delete('/ascensores/{elevator}', [ElevatorController::class, 'destroy'])->name('elevators.destroy');

    // Nueva ruta para cargar revisiones por AJAX ()
    Route::get('/ascensores/{elevator}/revisiones', [ElevatorController::class, 'getRevisions'])
        ->name('elevators.revisions');

   
    Route::put('/ascensores/{elevator}/revisions', [RevisionController::class, 'bulkUpdate'])->name('revisions.bulk');

    // PDF
    Route::get('/ascensores/{elevator}/poster.pdf', [PdfController::class, 'poster'])->name('elevators.poster');
});

Route::get('/p/{token}', [PublicController::class, 'show'])->name('public.elevator');

require __DIR__ . '/auth.php';
