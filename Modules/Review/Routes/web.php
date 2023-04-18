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
    ['namespace' => 'Frontend', 'middleware' => ['web', 'auth', 'frontpermssion']],
    function () {
        Route::post('customer/review/get-review-details', 'ReviewController@getReviewDetails')->name('customer.get.review-details');
        Route::post('company/review/get-review-details', 'ReviewController@getReviewDetails')->name('company.get.review-details');
        Route::post('company/review/add-review-user', 'ReviewController@addReviewUser')->name('company.add.review-user');
        Route::post('customer/review/add-review-user', 'ReviewController@addReviewUser')->name('customer.add.review-user');
        Route::post('owner/review/add-review-user', 'ReviewController@addReviewUser')->name('owner.review-user');
        Route::post('review-reply-vendor', 'ReviewController@ReviewReplyVendor')->name('review-reply-vendor');
    }
);

Route::prefix('admin')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {
        Route::resource('review', 'ReviewController', ['names' => 'review'])->except([
            'create', 'edit', 'store'
        ]);
        Route::post('review', 'ReviewController@index')->name('review.ajax');
    });
});
