<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'index'])->name('index');
    Route::post('/place', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'place'])->name('place');
});

Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/inventory', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'inventory'])->name('inventory');
    Route::get('/inventory/create', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'store'])->name('inventory.store');
});

Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/settings', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'settings'])->name('settings');
    Route::post('/settings', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'settingsUpdate'])->name('settings.update');
});

Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/orders', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}/print', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'printOrder'])->name('orders.print');
});

Route::middleware(['web', 'auth'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/orders/{order}', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'order'])->name('orders.show');
});

Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::get('/account', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'account'])->name('account');
    Route::post('/account', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'accountStore'])->name('account.store');
});


Route::middleware(['web', 'auth', 'splade'])->prefix('admin/pos')->name('admin.pos.')->group(function (){
    Route::post('/cart', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'cart'])->name('cart.index');
    Route::get('/cart/options', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'options'])->name('cart.options');
    Route::post('/cart/{cart}', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'update'])->name('cart.update');
    Route::delete('/cart', [\TomatoPHP\TomatoPos\Http\Controllers\TomatoPosController::class, 'clear'])->name('cart.clear');
});


