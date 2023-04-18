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

Route::prefix('notifications')->group(function() {
    Route::get('/', 'NotificationsController@index')->name('notification.index');
    Route::post('all-read-mark', 'NotificationsController@markReadAllNotification')->name('notification.markReadAllNotification');
    Route::post('mark-as-read', 'NotificationsController@markAsRead')->name('notification.markAsRead');
});
