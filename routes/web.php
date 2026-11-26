<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/create', [BusinessController::class, 'create'])->name('businesses.create');
Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
Route::post('/businesses/{business}/generate', [BusinessController::class, 'generate'])
    ->middleware('throttle:3,1')
    ->name('businesses.generate');

Route::post('/businesses/{business}/documents', [DocumentController::class, 'store'])->name('documents.store');
