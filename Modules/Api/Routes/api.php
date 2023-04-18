<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
//'middleware' =>'app_version'

$api->version(['v1'], ['namespace' => 'Modules\Api\Http\Controllers\Customer'],  function ($api) {

    //users route's 
    $api->post('customer-register','RegisterController@register');
    $api->post('customer-login','RegisterController@login');
    $api->post('forgot-password','ForgotpasswordController@sendResetLinkEmail');
    //homepage route's
    $api->post('search-property','Home\HomeController@searchProperty');
    $api->post('search-property-filter','Home\HomeController@searchPropertyFilter');
    $api->post('get-homepage-data','Home\HomeController@getHomepageData');
    $api->post('get-state','Home\HomeController@getState');
    $api->post('get-city','Home\HomeController@getCity');
    $api->post('get-location','Home\HomeController@getLocation');
    //profile route's
    $api->get('get-profile-form-data','GuestCommonController@getProfileFormData');
    $api->post('property-detail','Property\PropertyController@propertyDetail');
    $api->post('property-review-listing','Property\PropertyController@propertyReviewListing');
   
});


$api->version(['v1'],['namespace' => 'Modules\Api\Http\Controllers\Customer','middleware' => ['jwt.verify','auth:api','app_version']],  function ($api){
    //users routes
    $api->get('customer-logout','RegisterController@logout');

    //profile routes
    
    $api->get('customer-profile-details','Profile\ProfileController@profileDetails');
    $api->post('customer-profile-update','Profile\ProfileController@updateProfile');
    $api->post('delete-account','Profile\ProfileController@accountDelete');
    $api->post('change-password','Profile\ProfileController@changePassword');
    $api->post('signed-url','Profile\ProfileController@getSignedURL');
    $api->post('customer-mobile-update','Profile\ProfileController@updatePhoneNumber');

    //notifications routes
    $api->post('notification-list','Notification\NotificationController@notificationList');
    
    //home route's
    $api->post('home','Home\HomeController@home');

    //Property routes
    $api->post('add-favorite','Property\PropertyController@addFavorite');
    $api->post('my-wishlist','Property\PropertyController@myWishlist');
    
    $api->post('add-property-schedule','Property\Schedule\ScheduleController@addPropertySchedule');
    $api->post('get-schedule-property','Property\Schedule\ScheduleController@getScheduleProperty');
    $api->post('update-property-schedule','Property\Schedule\ScheduleController@updatePropertySchedule');

    $api->post('delete-schedule-property','Property\Schedule\ScheduleController@deleteScheduleProperty');
    $api->post('my-visits','Property\Schedule\ScheduleController@myVisits');
    $api->post('visit-property-detail','Property\Schedule\ScheduleController@visitPropertyDetail');
    $api->post('get-order-id','Property\PropertyController@scheduleGetOrderId');
    $api->post('make-payment','Property\PropertyController@makePayment');
    
    $api->post('get-booking-list','Property\Booking\BookingController@getBookingList');
    $api->post('review-booking','Property\Booking\BookingController@reviewBooking');
    $api->post('delete-booking','Property\Booking\BookingController@deleteBooking');
   
    $api->post('cancel-booking','Property\Booking\BookingController@cancelBooking');
    $api->post('booking-property-detail','Property\Booking\BookingController@bookingPropertyDetail');

    // Booking routes
    $api->post('get-booking-details', 'Property\PropertyController@bookingDetails');
    $api->post('add-booking', 'Property\PropertyController@addBooking');
    $api->post('apply-coupon', 'Property\PropertyController@applyOfferCode');
    $api->post('apply-agent-code', 'Property\PropertyController@applyAgentCode');

    // Schedule visit cancel
    $api->post('cancel-schedule-visit', 'Property\Schedule\ScheduleController@cancelScheduleMyVisit');
});

$api->version(['v1'], ['namespace' => 'Modules\Api\Http\Controllers'],  function ($api) {
    $api->get('get-stati-pages-urls', 'StaticPages\StaticPagesController@getCmsPagesLinks');
    $api->get('get-social-links', 'StaticPages\StaticPagesController@getSocialLinks');
    $api->get('get-playstore-links', 'StaticPages\StaticPagesController@getPlaystoreLinks');
});
