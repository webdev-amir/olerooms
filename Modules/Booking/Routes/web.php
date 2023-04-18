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
        'namespace' => 'Frontend',
        'as' => 'booking.',
        'middleware' => ['web', 'auth', 'frontpermssion']
    ],
    function () {
        Route::get('booking-details/{slug}', 'BookingController@bookingDetails')->name('details');
        Route::post('booking-add', 'BookingController@addBooking')->name('add');
        Route::post('get-room-options', 'BookingController@getRoomOptions')->name('room.options');
        Route::post('apply-offer-code', 'BookingController@applyOfferCode')->name('applycode');
        Route::post('apply-agent-code', 'BookingController@applyAgentCode')->name('applyAgentCode');
        Route::get('booking-payment/{slug}', 'BookingController@bookingPayment')->name('showPayment');
        Route::post('session-store', 'BookingController@storeProperty')->name('manageProperty.storeProperty');
        Route::resource('booking', 'BookingController', ['names' => 'manageProperty'])->except([
            'store',
        ]);
        Route::get('booking-successfull/{slug}', 'BookingController@propertyBookingPaymentSuccess')->name('payment.success');
    }
);


Route::group(
    [
        'prefix' => 'company',
        'namespace' => 'Frontend',
        'as' => 'company.booking.',
        'middleware' => ['web', 'auth', 'frontpermssion']
    ],
    function () {
        Route::get('booking-details/{slug}', 'CompanyBookingController@bookingDetails')->name('details');
        Route::post('booking-add', 'CompanyBookingController@addBooking')->name('add');
        Route::post('apply-offer-code', 'CompanyBookingController@applyOfferCode')->name('applycode');
        Route::post('apply-agent-code', 'CompanyBookingController@applyAgentCode')->name('applyAgentCode');
        Route::get('booking-payment/{slug}', 'CompanyBookingController@bookingPayment')->name('showPayment');
        Route::post('session-store', 'CompanyBookingController@storeProperty')->name('manageProperty.storeProperty');
        Route::resource('booking', 'CompanyBookingController', ['names' => 'manageProperty'])->except([
            'store',
        ]);
        Route::get('booking-successfull/{slug}', 'CompanyBookingController@propertyBookingPaymentSuccess')->name('payment.success');
    }
);

Route::prefix('admin')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {

        // Bookings
        Route::resource('booking', 'BookingController', ['names' => 'booking'])->except([
            'create', 'edit', 'store',
        ]);
        Route::get('booking/status/{slug}', 'BookingController@status')->name('booking.status');
        Route::post('booking', 'BookingController@index')->name('booking.ajax');

        // Cancel Bookings
        Route::get('cancelbooking', 'CancelBookingController@index')->name('booking.cancelbooking.index');
        Route::get('cancelbooking/{slug}', 'CancelBookingController@show')->name('booking.cancelbooking.show');
        Route::get('cancelbooking/status/{slug}', 'CancelBookingController@status')->name('booking.cancelbooking.status');
        Route::post('cancelbooking', 'CancelBookingController@index')->name('booking.cancelbooking.ajax');

        // Cancel ScheduleVisits
        Route::get('cancelschedulevisit', 'CancelScheduleVisitController@index')->name('booking.cancelschedulevisit.index');
        Route::get('cancelschedulevisit/{slug}', 'CancelScheduleVisitController@show')->name('booking.cancelschedulevisit.show');
        Route::get('cancelschedulevisit/status/{slug}', 'CancelScheduleVisitController@status')->name('booking.cancelschedulevisit.status');
        Route::post('cancelschedulevisit', 'CancelScheduleVisitController@index')->name('booking.cancelschedulevisit.ajax');
    });
});
