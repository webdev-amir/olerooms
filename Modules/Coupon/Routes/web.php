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
        Route::resource('coupon', 'CouponController',['names'=>'coupon'])->except([
           'show'
        ]);
        Route::post('coupon/media/upload', 'CouponController@saveMedia')->name('coupon.mediaStore');
        Route::get('coupon/status/{slug}', 'CouponController@status')->name('coupon.status');
    });
});
