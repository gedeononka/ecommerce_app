<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;


    
// Afficher la liste des produits (GET /client/products)
Route::get('client/products', [ProductController::class, 'index'])->name('products.index');

// Afficher un produit individuel (GET /client/products/{product})
Route::get('client/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('client/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('client/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::put('client/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('client/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('client/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('client/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('client/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('client/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('client/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('client/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])
    ->name('orders.cancel');
Route::get('/orders/{order}/reorder', [OrderController::class, 'reorder'])
     ->name('orders.reorder');
