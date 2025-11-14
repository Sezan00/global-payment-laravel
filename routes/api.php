<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PurposeOfTransferController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\SourceOfFundController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/source-of-funds', [CountryController::class, 'index']);

Route::get('/relations', [RelationController::class, 'index']);
Route::get('/sourcefunds', [SourceOfFundController::class, 'index']);
Route::get('/purposefunds', [PurposeOfTransferController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});