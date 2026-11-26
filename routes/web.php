<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PublishedSiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/create', [BusinessController::class, 'create'])->name('businesses.create');
Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/sites/{slug}', [PublishedSiteController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('sites.show');
Route::post('/businesses/{business}/generate', [BusinessController::class, 'generate'])
    ->middleware('throttle:3,1')
    ->name('businesses.generate');
Route::patch('/businesses/{business}/draft', [BusinessController::class, 'updateDraft'])
    ->name('businesses.draft.update');
Route::post('/businesses/{business}/publish', [BusinessController::class, 'publish'])
    ->middleware('throttle:10,1')
    ->name('businesses.publish');

Route::post('/businesses/{business}/documents', [DocumentController::class, 'store'])->name('documents.store');
