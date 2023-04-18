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
Route::group(['prefix'=>'owner'],function(){
    Route::group(['namespace' => 'Frontend', 'middleware' => ['web']], function () {
        Route::get('/','PropertyOwnerHomeController@home')->name("propertyowner.home");
    });
});

Route::group(
    [
        'prefix' => 'owner',
        'namespace' => 'Frontend',
        'middleware' => ['profilecompleteverify','web','auth','frontpermssion']
    ],
    function () {
        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
        Route::get('dashboard', 'MyDashboardController@myDashboard')->name('vendor.dashboard');
        Route::get('myprofile', 'MyDashboardController@myprofile')->name('vendor.dashboard.myprofile');
        Route::get('change-password', 'MyDashboardController@changePassword')->name('vendor.dashboard.changePassword');
        Route::post('update-password', 'MyDashboardController@updatePassword')->name('vendor.dashboard.updatePassword');
        Route::post('update-profile', 'MyDashboardController@updateProfile')->name('vendor.dashboard.updateProfile');
        Route::post('profile/upload', 'MyDashboardController@saveMedia')->name('vendor.dashboard.uploadProfile');
        //Route::get('my-payment', 'MyDashboardController@managePayment')->name('dashboard.managePayment');
        Route::get('notifications', 'MyDashboardController@notifications')->name('vendor.dashboard.notifications');

        //Route::get('wishlist','UserWishListController@index')->name("vendor.dashboard.wishlist");

        Route::get('complete-your-profile', 'MyDashboardController@getCompleteProfile')->name('vendor.completeProfileVerification');
        Route::post('complete-your-profile', 'MyDashboardController@submitCompleteProfile')->name('vendor.submitCompleteProfileVerification');
        Route::post('deactivate-account', 'MyDashboardController@deactivateAccount')->name('vendor.dashboard.deactivateAccount');
        Route::post('delete-account', 'MyDashboardController@deleteAccount')->name('vendor.dashboard.deleteAccount');
        // Dashboard MyProperty
        Route::get('myproperty', 'MyDashboardController@myproperty')->name('vendor.myproperty');
        Route::post('myproperty/status', 'MyDashboardController@mypropertyStatus')->name('vendor.myproperty.status');

        Route::post('myproperty/upload-selfie', 'MyDashboardController@mypropertyUploadSelfie')->name('vendor.myproperty.uploadSelfie');
        
        Route::post('myproperty/upload-selfie/save', 'MyDashboardController@mypropertyUploadSelfieOrAgreementSave')->name('vendor.myproperty.uploadSelfie.save');
        Route::post('myproperty/upload-agreement', 'MyDashboardController@mypropertyUploadAgreement')->name('vendor.myproperty.uploadAgreement');

        Route::post('myproperty/upload-agreement/save', 'MyDashboardController@mypropertyUploadSelfieOrAgreementSave')->name('vendor.myproperty.uploadAgreement.save');
        Route::post('myproperty/delete', 'MyDashboardController@deleteMyProperty')->name('vendor.myproperty.delete');
        Route::post('myproperty/offers', 'MyDashboardController@getAllOffers')->name('vendor.myproperty.offers');
        Route::post('myproperty/offerapply', 'MyDashboardController@offerApply')->name('vendor.myproperty.offerapply');
        Route::get('myproperty/{filename}', 'MyDashboardController@downloadAgreement')->name('vendor.myproperty.download');
        Route::get('mybooking', 'MyDashboardController@myBookings')->name('vendor.dashboard.mybookings');

        Route::get('mybooking/details/{slug}', 'MyDashboardController@myBookingsDetails')->name('vendor.dashboard.mybookings.details');

        Route::get('myvisit', 'MyDashboardController@myVisits')->name('vendor.dashboard.myvisits');
        Route::get('myvisit/details/{visit_slug}', 'MyDashboardController@myvisitDetails')->name('vendor.dashboard.myvisit.details');

        Route::get('myearnings', 'MyDashboardController@myEarnings')->name('vendor.dashboard.myEarnings');
        Route::get('myreviews', 'MyDashboardController@myReviews')->name('vendor.dashboard.myReviews');

        //Booking Requests Approved or Reject By Property Owner
        Route::get('booking-requests/accept/{bookingid}', 'MyDashboardController@acceptBookingRequest')->name('vendor.bookingRequest.acceptBookingRequest');
        Route::get('booking-requests/reject/{bookingid}', 'MyDashboardController@rejectBookingRequest')->name('vendor.bookingRequest.rejectBookingRequest');
        Route::post('vendor/logout', 'MyDashboardController@vendorLogout')->name('vendor.logout');
    }
);

Route::group(
    [
        'prefix' => 'owner',
        'namespace' => 'Frontend',
        'middleware' => ['web','auth','frontpermssion']
    ],
    function () {
        Route::post('media/upload-selfy-logo', 'MyDashboardController@uploadSelfyAndLogo')->name('vendor.uploadSelfyAndLogo');
        Route::post('media/upload-user-image-pdf', 'MyDashboardController@uploadUserImagePdf')->name('vendor.uploadUserImagePdf');
    }
);
