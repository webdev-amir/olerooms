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

Route::prefix('admin')->group(function () {
	Route::middleware(['web'])->group(function () {
		Route::resource('faq', 'FaqController')->except([
			'show'
		]);
	});
	Route::get('faq/status/{slug}', 'FaqController@status')->name('faq.status');
});
