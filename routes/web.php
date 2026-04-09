<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');

Route::get('/checkout' , function(){
    return view ('checkout');
});

Route::post('/checkout', [OrderController::class, 'store']);