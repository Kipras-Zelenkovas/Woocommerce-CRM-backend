<?php

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

    Route::get('', [Manager::class, 'index']);
    Route::get('test', [Manager::class, 'test']);
    Route::get('orders', [Manager::class, 'orders']);
    Route::get('top_sellers', [Manager::class, 'top_sellers']);
    Route::get('all', [Manager::class, 'all']);
    Route::get('order', [Manager::class, 'find']);
    Route::put('status', [Manager::class, 'change_status']);
});
