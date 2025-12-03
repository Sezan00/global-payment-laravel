<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExhangeRatesController;
use App\Http\Controllers\PurposeOfTransferController;
use App\Http\Controllers\QuotationsController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\SourceOfFundController;
use App\Http\Controllers\SuportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/country', [CountryController::class, 'index']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    

    Route::get('/relations', [RelationController::class, 'index']);
    Route::get('/sourcefunds', [SourceOfFundController::class, 'index']);
    Route::get('/purposefunds', [PurposeOfTransferController::class, 'index']);


    //currencies selected route
    Route::get('/user/sendercurrencies', [SuportController::class, 'getSenderSupportedCurrencies']);
    Route::get('/receiver/countries', [SuportController::class, 'indexCountry']);
    Route::get('/receiver/countries/{country}/currencies', [SuportController::class, 'getReceiverCurrencies']);

    Route::get('/available-countries', [SuportController::class, 'getAvailableCountires']);


    // exchange currencies value 
    Route::get('/exhange-rate', [RateController::class, 'getRate']);

    //exhange rate store by user
    Route::post('/save-exchange', [ExhangeRatesController::class, 'ExchangeRateStore']);

    //insert data to quotations table
    Route::post('/quotation-store', [QuotationsController::class, 'store']);

    //for confirm rate and amount show 

    Route::get('confirm-cur/{id}', [QuotationsController::class, 'index']);
});