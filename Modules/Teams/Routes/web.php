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

Route::prefix('admin/')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {
        Route::resource('teams', 'TeamsController', ['names' => 'teams']);
        Route::post('teams/media/upload', 'TeamsController@saveMedia')->name('teams.mediaStore');
        Route::get('teams/status/{slug}', 'TeamsController@status')->name('teams.status');
    });
});
