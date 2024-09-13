<?php

use Illuminate\Support\Facades\Route;
use Signalmetrics\Signal\Controllers\IngestionController;


Route::get('/analytics/event', [IngestionController::class, 'store']);
Route::post('/analytics/event', [IngestionController::class, 'store']);

Route::view('/analytics', 'signal::index');