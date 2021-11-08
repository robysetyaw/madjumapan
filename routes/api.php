<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('api')->group(function () {
    Route::post('login', [UserController::class, 'login']);

    Route::post(
        'gudang/insert',
        [GudangController::class, 'insert_item']
    )->middleware('auth:sanctum');

    Route::get(
        'gudang/items/transactions',
        [GudangController::class, 'get_transaction']
    )->middleware('auth:sanctum');

    Route::get(
        'gudang/items/stocks',
        [GudangController::class, 'get_stocks']
    )->middleware('auth:sanctum');

});
