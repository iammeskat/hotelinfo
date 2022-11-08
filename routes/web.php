<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;

Route::get('/', [DashboardController::class, 'dashboard']);
Route::post('/', [DashboardController::class, 'dashboard'])->name('guest-data');
Route::get('/images/{path}', [ImageController::class, 'getImage']);
