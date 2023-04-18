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
	Route::middleware(['web','auth'])->group(function () {
		Route::resource('slider', 'SliderController',['names'=>'slider']);
		//Upload Images By Ajax
    	Route::post('slider/media/upload', 'SliderController@saveMedia')->name('slider.mediaStore');
    	Route::get('slider/status/{slug}', 'SliderController@status')->name('slider.status');
    });
});