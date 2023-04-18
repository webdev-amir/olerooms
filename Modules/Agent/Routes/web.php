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

/*Route::prefix('agent')->group(function() {
    Route::get('/', 'AgentController@index');
});*/

Route::group(['prefix' => 'agent'], function () {
    Route::group(['namespace' => 'Frontend', 'middleware' => ['web']], function () {
        Route::get('/', 'AgentHomeController@home')->name("agent.home");
    });
});

Route::group(
    [
        'prefix' => 'agent',
        'namespace' => 'Frontend',
        'as' => 'agent.',
        'middleware' => ['web', 'auth', 'frontpermssion']
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::get('dashboard', 'MyDashboardController@myDashboard')->name('dashboard');
        Route::get('myprofile', 'MyDashboardController@myprofile')->name('dashboard.myprofile');
        Route::get('change-password', 'MyDashboardController@changePassword')->name('dashboard.changePassword');
        Route::post('update-password', 'MyDashboardController@updatePassword')->name('dashboard.updatePassword');
        Route::post('update-profile', 'MyDashboardController@updateProfile')->name('dashboard.updateProfile');
        Route::post('profile/upload', 'MyDashboardController@saveMedia')->name('dashboard.uploadProfile');
        Route::post('update-bank-details', 'MyDashboardController@updateBankDetails')->name('dashboard.updateBankDetails');
        Route::post('profile/upload-bank-images', 'MyDashboardController@uploadBankImages')->name('dashboard.uploadBankImages');
        Route::post('deactivate-account', 'MyDashboardController@deactivateAccount')->name('dashboard.deactivateAccount');
        Route::post('delete-account', 'MyDashboardController@deleteAccount')->name('dashboard.deleteAccount');
        Route::get('mybooking', 'MyDashboardController@myBookings')->name('dashboard.mybookings');
        Route::get('mybooking/details/{slug}', 'MyDashboardController@myBookingsDetails')->name('dashboard.mybookings.details');
        Route::get('myearnings', 'MyDashboardController@myEarnings')->name('dashboard.myEarnings');
        Route::post('dashbaord/send-redeem-credit-request', 'MyDashboardController@sendRedeemCreditRequest')->name('dashboard.sendRedeemCreditRequest');
        Route::get('notifications', 'MyDashboardController@notifications')->name('dashboard.notifications');
    
    }
);
