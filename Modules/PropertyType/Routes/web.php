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
        Route::resource('propertytype', 'PropertyTypeController',['names'=>'propertytype'])->except([
           'show'
        ]);
        Route::post('propertytype/media/upload', 'PropertyTypeController@saveMedia')->name('propertytype.mediaStore');
        Route::get('propertytype/status/{slug}', 'PropertyTypeController@status')->name('propertytype.status');
    });
});
