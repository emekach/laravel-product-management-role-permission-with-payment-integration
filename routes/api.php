<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PayController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::get('product', [ProductController::class, 'index']);

Route::post('purchase', [ProductController::class, 'purchase']);

Route::get('product/{id}', [ProductController::class, 'show']);

Route::post('pays', [PaymentController::class, 'makePayment'])->middleware(['auth:sanctum']);

Route::post('register', [AuthController::class, 'register']);

Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);




Route::get('payment/approval', [PaymentController::class, 'approval'])->name('approval');
Route::get('payment/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
