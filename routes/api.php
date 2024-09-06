<?php

use App\Http\API\v1\Controllers\AuthController;
use App\Http\API\v1\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        #auth
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/logout', [AuthController::class, 'logout']);
        #payment and transactions
        Route::post('/process-payment', [PaymentController::class, 'processPayment']);
        Route::get('transaction/{transaction}', [PaymentController::class, 'transaction']);
        Route::get('transactions', [PaymentController::class, 'transactions']);
    });
});
