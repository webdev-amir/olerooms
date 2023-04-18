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
        Route::resource('trustedcustomers', 'TrustedCustomersController',['names'=>'trustedcustomers'])->except([
           'show'
        ]);
        Route::post('trustedcustomers/media/upload', 'TrustedCustomersController@saveMedia')->name('trustedcustomers.mediaStore');
        Route::get('trustedcustomers/status/{slug}', 'TrustedCustomersController@status')->name('trustedcustomers.status');
    });
});
