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
		Route::resource('settings', 'SettingsController',['names'=>'settings'])->except([
            'show'
        ]);
        Route::post('settings/media/upload', 'SettingsController@saveMedia')->name('settings.mediaStore');
    });
});