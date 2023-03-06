<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Manager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::prefix('/')->group(function () {

    Route::get('', [Manager::class, 'index'])->middleware('auth:sanctum');
    Route::get('test', [Manager::class, 'test'])->middleware('auth:sanctum');
    Route::get('orders', [Manager::class, 'orders'])->middleware('auth:sanctum');
    Route::get('top_sellers', [Manager::class, 'top_sellers'])->middleware('auth:sanctum');
    Route::get('all', [Manager::class, 'all'])->middleware('auth:sanctum');
    Route::get('order', [Manager::class, 'find'])->middleware('auth:sanctum');
    Route::put('status', [Manager::class, 'change_status'])->middleware('auth:sanctum');

    Route::post('/login', [Auth::class, 'login'])->middleware('guest');
    Route::post('/register', [Auth::class, 'register'])->middleware('guest');
    Route::post('/logout', [Auth::class, 'logout'])->middleware('auth:sanctum');
});
