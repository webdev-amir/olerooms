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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('downloadS3File', [App\Http\Controllers\HomeController::class, 'downloadS3File'])->name('downloads3file');
Route::post('store-property-session-id', [App\Http\Controllers\HomeController::class, 'storePropertySessionId'])->name('storePropertySessionId');

Route::get('cache-optimise', [App\Http\Controllers\HomeController::class, 'cacheOptimize'])->name('cacheOptimize');
Route::get('cache-clear', [App\Http\Controllers\HomeController::class, 'cacheClear'])->name('cacheClear');
Route::get('test-algo', [App\Http\Controllers\HomeController::class, 'testAlgo'])->name('testAlgo');
