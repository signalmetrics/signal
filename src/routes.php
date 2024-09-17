<?php

use Illuminate\Support\Facades\Route;
use Signalmetrics\Signal\Controllers\IngestionController;

Route::middleware('throttle:signal.throttle')->group(function () {
    Route::get('/analytics/event', [IngestionController::class, 'store']);
    Route::post('/analytics/event', [IngestionController::class, 'store']);
});


Route::view('/analytics', 'signal::index');