<?php

use App\Http\Controllers\{ElevatorController, RevisionController, PublicController, PdfController};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check() 
        ? redirect()->route('elevators.index') 
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('elevators.index');
    })->name('dashboard');
    
    Route::get('/elevators', [ElevatorController::class, 'index'])->name('elevators.index');
    Route::get('/elevators/{elevator}', [ElevatorController::class, 'show'])->name('elevators.show');
    Route::post('/elevators', [ElevatorController::class, 'store'])->name('elevators.store');
    Route::put('/elevators/{elevator}', [ElevatorController::class, 'update'])->name('elevators.update');
    Route::delete('/elevators/{elevator}', [ElevatorController::class, 'destroy'])->name('elevators.destroy');
    Route::put('/elevators/{elevator}/revisions', [RevisionController::class, 'bulkUpdate'])->name('revisions.bulk');
    Route::get('/elevators/{elevator}/poster.pdf', [PdfController::class, 'poster'])->name('elevators.poster');
});

Route::get('/p/{token}', [PublicController::class, 'show'])->name('public.elevator');

 
require __DIR__.'/auth.php';
