<?php

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
Route::prefix('admin')->group( function() {
	Route::middleware(['web'])->group(function () {
        Route::get('/', 'DashboardController@index')->name('backend.index');
        Route::get('dashboard', 'DashboardController@index')->name('backend.dashboard');
    });
});