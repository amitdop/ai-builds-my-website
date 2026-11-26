<?php

use App\Http\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/create', [BusinessController::class, 'create'])->name('businesses.create');
Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
