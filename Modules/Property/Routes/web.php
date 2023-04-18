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
        Route::resource('property', 'PropertyController', ['names' => 'property'])->except([
            'create', 'show', 'edit', 'store', 'destroy'
        ]);
        //Upload Images By Ajax
        Route::get('property/{slug}', 'PropertyController@showProperty')->name('property.details');
        Route::post('property/status', 'PropertyController@status')->name('property.changeStatus');
        Route::post('tasks/featured', 'PropertyController@featuredProperty')->name('property.featured');

        Route::get('property/lists/{id}', 'PropertyController@userIndex')->name('property.userindex');
    });
});


Route::post('owner/property/media/upload/{user_id}', 'PropertyController@saveMedia')->name('property.mediaStore');

Route::group(
    [
        'prefix' => 'owner',
        'namespace' => 'Frontend',
        'middleware' => ['profilecompleteverify', 'web', 'auth', 'frontpermssion']
    ],
    function () {
        Route::post('session-store', 'MyPropertyController@storeProperty')->name('manageProperty.storeProperty');
        Route::resource('property', 'MyPropertyController', ['names' => 'manageProperty'])->except([
            'store', 'index','show'
        ]);
        Route::get('property-successfull/{slug}', 'MyPropertyController@propertyAddSuccess')->name('manageProperty.success');
        Route::post('property/room-images-upload/{user_id}', 'MyPropertyController@uploadRoomImages')->name('manageProperty.uploadRoomImages');
        Route::post('property/room-video-upload/{user_id}', 'MyPropertyController@uploadRoomVideo')->name('manageProperty.uploadRoomVideo');
        Route::post('property/upload-agreement/{user_id}', 'MyPropertyController@uploadAgreement')->name('manageProperty.uploadAgreement');
    }
);


Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Frontend',
        'middleware' => ['web', 'auth']
    ],
    function () {
        Route::resource('property', 'MyPropertyController', ['names' => 'manageProperty'])->except([
            'store', 'index',
        ]);
        Route::post('property/room-images-upload/{user_id}', 'MyPropertyController@uploadRoomImages')->name('manageProperty.uploadRoomImages');
        Route::post('property/room-video-upload/{user_id}', 'MyPropertyController@uploadRoomVideo')->name('manageProperty.uploadRoomVideo');
        Route::post('property/upload-agreement/{user_id}', 'MyPropertyController@uploadAgreement')->name('manageProperty.uploadAgreement');
    }
);

Route::post('admin/property/media/upload/{user_id}', 'PropertyController@saveMedia')->name('admin.property.mediaStore');


Route::group(
    [
        'prefix' => '',
    ],
    function () {
        Route::get('search', 'Frontend\FrontendPropertyController@index')->name('search');
        Route::get('find-property', 'Frontend\FrontendPropertyController@findSpace')->name('property.findSpace');
        Route::get('property-map', 'Frontend\FrontendPropertyController@mapSearch')->name('property.map');
        Route::get('share-detail\{slug}', 'Frontend\FrontendPropertyController@shareDetails')->name('property.sharedetails');
        Route::resource('property', 'Frontend\MyPropertyController', ['names' => 'manageProperty'])->only([
            'show',
        ]);
        Route::get('property/autocomplete/get-locations', 'Frontend\FrontendPropertyController@getAutocompleteLocationsLists')->name('property.getAutocompleteLocationsLists');
    }
);
