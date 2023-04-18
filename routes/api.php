<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$api->version(['v1'], ['namespace' => 'App\Http\Controllers\Api'],  function ($api) {
     $api->post('subscrive-newsletter', 'Homepage\HomepageController@subscrivedMailchimp')->name('subscrive.newsletter');
     $api->get('get-social-links', 'Homepage\HomepageController@getSocialLinks')->name('getSocialLinks');
     $api->get('get-map-contact-details', 'Homepage\HomepageController@getMapContactDetails')->name('getMapContactDetails');
     $api->get('pages/{slug}', 'Homepage\HomepageController@getCmsPages')->name('getCmsPages');
     $api->get('get-sidebr-notifications', 'Homepage\HomepageController@getUserNotifications')->middleware('web');

     $api->post('get-state-cities', 'Homepage\HomepageController@getStateCities')->name('getStateCities');
     $api->post('get-cities-area',  'Homepage\HomepageController@getCitiesArea')->name('getCitiesArea');
});
