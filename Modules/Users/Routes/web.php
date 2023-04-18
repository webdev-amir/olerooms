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
    Route::group(['namespace' => 'BackEnd', 'middleware' => ['web', 'auth']], function () {
        Route::resource('admin',   'SubadminController',    ['names' => 'subadmin'])->except([]);

        Route::resource('agent', 'AgentsController', ['names' => 'agent'])->except(['destroy']);
        Route::get('agent/update-status/{slug}', 'AgentsController@status')->name('agent.status');
        Route::post('agent/delete/{id}', 'AgentsController@destroy')->name('agent.delete');

        Route::resource('company', 'CompanyController', ['names' => 'company'])->except(['destroy']);
        Route::get('company/update-status/{slug}', 'CompanyController@status')->name('company.status');
        Route::post('company/delete/{id}', 'CompanyController@destroy')->name('company.delete');

        Route::resource('vendor', 'VendorsController', ['names' => 'vendor'])->except(['destroy']);
        Route::get('vendor/update-status/{slug}', 'VendorsController@status')->name('vendor.status');
        Route::get('vendor/document-verification/{id}', 'VendorsController@documentVerificationStatusView')->name('vendor.documentVerificationStatus');
        Route::post('vendor/document-verification-update/{id}', 'VendorsController@documentVerificationStatusUpdate')->name('vendor.documentVerificationStatus.update');
        Route::post('vendor/delete/{id}', 'VendorsController@destroy')->name('vendor.delete');


        Route::resource('customer', 'UsersController',    ['names' => 'users'])->except(['destroy']);
        Route::post('customer/delete/{id}', 'UsersController@destroy')->name('users.delete');

        Route::get('user/update-status/{slug}', 'UsersController@status')->name('users.status');
        Route::post('user/media/upload', 'UsersController@saveMedia')->name('users.uploadProfile');
        Route::post('user/user-change-password', 'UsersController@storeChangeUserPassword')->name('users.storeChangeUserPassword');
    });
}); 


Route::group(['namespace' => 'Frontend', 'middleware' => ['web', 'auth']], function () {
    Route::get('customer/wishlist', 'UserWishListController@index')->name("dashboard.wishlist");
    Route::get('customer/wishlist/remove', 'UserWishListController@remove')->name("user.wishList.remove");
    Route::post('customer/wishlist', 'UserWishListController@handleWishList')->name("user.wishList.handle");
});

Route::group(['prefix' => 'profile'], function () {
    Route::group(['namespace' => 'Frontend', 'middleware' => ['web']], function () {
        Route::match(['get'], '/{id}', 'ProfileController@profile')->name("user.profile");
        Route::match(['get'], '/{id}/reviews', 'ProfileController@allReviews')->name("user.profile.reviews");
        Route::match(['get'], '/{id}/services', 'ProfileController@allServices')->name("user.profile.services");
    });
});
