<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\AuthApiController;


// Route publique pour la connexion
Route::post('/login', [AuthApiController::class, 'login']);

// Routes protégées par auth:sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Cart
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart/add/{productId}', [CartApiController::class, 'add']);
    Route::put('/cart/update/{cartItemId}', [CartApiController::class, 'update']);
    Route::delete('/cart/remove/{cartItemId}', [CartApiController::class, 'remove']);
    Route::delete('/cart/clear', [CartApiController::class, 'clear']);

    // Orders
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::get('/orders/create', [OrderApiController::class, 'create']);
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::get('/orders/{order}/invoice', [OrderApiController::class, 'invoice']);
    Route::put('/orders/{order}', [OrderApiController::class, 'update']);
    Route::delete('/orders/{order}/cancel', [OrderApiController::class, 'cancel']);
    Route::post('/orders/{order}/reorder', [OrderApiController::class, 'reorder']);

    // Products (ici protégées, tu peux aussi les rendre publiques selon besoin)
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{product}', [ProductApiController::class, 'show']);
});

