<?php

use App\Http\Controllers\NamePredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NamePredictionController::class, 'index'])->name('predictions.index');
Route::post('/predictions', [NamePredictionController::class, 'store'])->name('predictions.store');
Route::get('/predictions/{prediction}', [NamePredictionController::class, 'show'])->name('predictions.show');
Route::delete('/predictions/{prediction}', [NamePredictionController::class, 'destroy'])->name('predictions.destroy');
