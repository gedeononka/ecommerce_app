<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StatsController;
use Illuminate\Support\Facades\Route;

// Produits
Route::resource('products', ProductController::class)
    ->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

// Catégories (sans la route show)
Route::resource('categories', CategoryController::class)
    ->except(['show'])
    ->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

// Commandes (index, show, update uniquement)
Route::resource('orders', OrderController::class)
    ->only(['index', 'show', 'update','edit'])
    ->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show',
        'update' => 'admin.orders.update',
        'edit'=>'admin.orders.edit',
    ]);

// Utilisateurs (index, show, edit, update uniquement)
Route::resource('users', UserController::class)
    ->only(['index', 'show', 'edit', 'update'])
    ->names([
        'index' => 'admin.users.index',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
    ]);

// Statistiques
Route::get('stats', [StatsController::class, 'index'])->name('admin.stats.index');
