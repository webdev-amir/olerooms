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
Route::group(
    [
        'prefix' => 'customer',
        'as' => 'customer.',
        'middleware' => ['web','auth','frontpermssion']
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::get('myprofile', 'MyDashboardController@myDashboard')->name('dashboard');
        Route::get('myprofile', 'MyDashboardController@myprofile')->name('dashboard.myprofile');
        Route::get('change-password', 'MyDashboardController@changePassword')->name('dashboard.changePassword');
        Route::post('update-password', 'MyDashboardController@updatePassword')->name('dashboard.updatePassword');
        Route::post('update-profile', 'MyDashboardController@updateProfile')->name('dashboard.updateProfile');
        Route::post('update-mobile', 'MyDashboardController@updateMobile')->name('dashboard.updateMobile');
        Route::post('user/profile/upload', 'MyDashboardController@saveMedia')->name('dashboard.uploadProfile');
        //Route::get('my-payment', 'MyDashboardController@managePayment')->name('dashboard.managePayment');
        Route::get('notifications', 'MyDashboardController@notifications')->name('dashboard.notifications');
        Route::post('delete-user-account', 'MyDashboardController@deleteUserAccount')->name('dashboard.deleteAccount');
        Route::get('mybooking', 'MyDashboardController@mybooking')->name('dashboard.mybooking');
        Route::get('mybooking/details/{slug}', 'MyDashboardController@myBookingsDetails')->name('dashboard.mybookings.details');
        Route::get('myvisits', 'MyDashboardController@myvisit')->name('dashboard.myvisit');
        Route::get('myvisit/details/{visit_slug}', 'MyDashboardController@myvisitDetails')->name('dashboard.myvisit.details');
        Route::post('mybooking/cancel-booking-request', 'MyDashboardController@cancellBookingRequest')->name('dashboard.mybookings.cancellBookingRequest');
        Route::post('myvisit/cancel-visit-request', 'MyDashboardController@cancellVisitRequest')->name('dashboard.myvisits.cancellVisitRequest');
    }
);
