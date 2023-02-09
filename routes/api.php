<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\WalletsController;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthenticationController::class, 'login']);



Route::middleware('auth:sanctum')->group(function (){

    // --- Get Data Wallet
    Route::get('history_wallet', [WalletsController::class, 'index']);
    
    // --- Top Up Data Wallet
    Route::post('top_up', [WalletsController::class, 'store']);


    // --- Withdraw Data Pallet
    Route::post('withdraw', [WalletsController::class, 'update_wallet']);
});
