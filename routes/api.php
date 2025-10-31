<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ElevatorController, RevisionController, PublicController};

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('elevators', ElevatorController::class);
    Route::get('elevators/{elevator}/revisions', [RevisionController::class, 'index']);
    Route::put('elevators/{elevator}/revisions/bulk', [RevisionController::class, 'bulk']);
    Route::put('elevators/{elevator}/revisions/{year}/{month}', [RevisionController::class, 'setOne']);
});

Route::prefix('public')->group(function () {
    Route::get('elevators/{token}', [PublicController::class, 'elevator']);
    Route::get('elevators/{token}/revisions', [PublicController::class, 'revisions']);
});
