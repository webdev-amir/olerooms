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
    Route::middleware(['web', 'auth'])->group(function () {
        Route::resource('payments', 'PaymentController', ['names' => 'payment'])->only([
            'index', 'show'
        ]);
    });
});


Route::group(
    [
        'prefix' => 'customer',
        'as' => 'booking.',
        'middleware' => ['auth']
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::post('payment-success', 'Frontend\RozarPayPaymentController@paymentResponse')->name('payment');
        Route::post('orderid-generate', 'Frontend\RozarPayPaymentController@orderIdGenerate')->name('orderIdGenerate');
    },

);


Route::group(
    [
        'prefix' => 'company',
        'as' => 'company.booking.',
        'middleware' => ['auth']
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::post('payment-success', 'Frontend\RozarPayCompanyPaymentController@paymentResponse')->name('payment');
        Route::post('orderid-generate', 'Frontend\RozarPayCompanyPaymentController@orderIdGenerate')->name('orderIdGenerate');
    }
);

Route::group(
    [
        //'prefix' => 'customer',
        'as' => 'booking.'
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::post('webhook/payment-response', 'Frontend\RozarPayPaymentController@paymentWebhookResponse')->name('payment.webhook');
    }
);
