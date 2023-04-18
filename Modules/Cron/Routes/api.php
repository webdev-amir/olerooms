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
$api->version(['v1'], ['namespace' => 'Modules\Cron\Http\Controllers'],  function ($api) {

     //Need To set Cron In every hours per [Set Per Minutes Cron]
     //URL : http://202.157.76.21:6300/php7.3/olerooms/api/cron/send-test-mail-and-notification-cron
     //Note: Send Mail & Notification to Admin For Testing.
     $api->get('cron/send-test-mail-and-notification-cron', 'CronController@sendTestMailandNotificationCron');

     //Need To set Cron In every Minutes per [Set Per minues Cron]
     //URL : http://202.157.76.21:6300/php7.3/olerooms/api/cron/mark-auto-confirmed-booking-if-vendor-not-confirmed
     //Note: make Paid To Completed If Booking Period Is Passed Or No Any Emi To Paid
     $api->get('cron/mark-auto-confirmed-booking-if-vendor-not-confirmed', 'CronController@makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor');

     //Need To set Cron In every hours per [Set Per hours Cron]
     //URL : http://202.157.76.21:6300/php7.3/olerooms/api/cron/mark-confirmed-completed-if-check-out-date-is-passed
     //Note: make Paid To Completed If Booking Period Is Passed Or No Any Emi To Paid
     $api->get('cron/mark-confirmed-completed-if-check-out-date-is-passed', 'CronController@makeConfirmedToCompletedIfBookingCheckOutDateIsPassed');

     $api->get('cron/make-search-address-from-pro-state-city-ids', 'CronController@makeSearchAddressForSearch');
});
