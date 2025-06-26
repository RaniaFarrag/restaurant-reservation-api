<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('check-availability', [TableController::class, 'checkAvailability']);
Route::post('reserve-table', [TableController::class, 'reserveTable']);
Route::get('menu-items', [MealController::class, 'listMenuItems']);

//require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('place-order', [OrderController::class, 'placeOrder']);
    Route::post('pay', [OrderController::class, 'pay']);
});