<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\MercadoPagoController;
use App\Http\Controllers\MessagesNotificationsController;

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

// Authentication Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Product Routes
Route::get('products', [ProductController::class, 'index']);
Route::get('products/infinite', [ProductController::class, 'infiniteScroll']);
Route::get('products/category/{categoryId}', [ProductController::class, 'getByCategory']);
Route::get('products/clinic/{clinicId}', [ProductController::class, 'getByClinic']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/get-unread-messages', [MessagesNotificationsController::class, 'getUnreadMessages']);


    // MercadoPago Routes
    Route::prefix('mercadopago')->group(function () {
        Route::post('/orders', [MercadoPagoController::class, 'createOrder']);
        Route::get('/orders/{orderId}', [MercadoPagoController::class, 'getOrderStatus']);
    });
});

Route::post('/test/send-message', [MessagesNotificationsController::class, 'sendTestMessage']);
