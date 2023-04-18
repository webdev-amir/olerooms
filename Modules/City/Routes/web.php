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
        Route::resource('state', 'StateController', ['names' => 'state'])->except([
            'show', 'index'
        ]);
        Route::get('states', 'StateController@ajaxIndex')->name('state.ajax.index');
        Route::get('state/{id}/city', 'CityController@cityStateList')->name('state.city');
        Route::get('state', 'StateController@index')->name('state.index');
        Route::get('state/change-status/{id}', 'StateController@changeStatus')->name('state.status');


        Route::resource('city', 'CityController', ['names' => 'city'])->except([
            'show', 'create', 'store'
        ]);
        // Route::get('state/{stateId}/city/status/{id}', 'CityController@cityStatus')->name('state.city.status');
        Route::get('city/create/{stateId}', 'CityController@create')->name('city.create');
        Route::post('city/store/{stateId}', 'CityController@store')->name('city.store');
        Route::get('state/{stateId}/city/edit/{id}', 'CityController@editCity')->name('state.city.edit');
        Route::patch('state/{stateId}/city/update/{id}', 'CityController@updateCity')->name('state.city.update');
        Route::post('city/media/upload', 'CityController@saveMedia')->name('city.mediaStore');
        Route::get('city/status/{id}', 'CityController@cityStatus')->name('city.status');


        Route::get('state/{stateId}/city/{id}/areas', 'AreaController@cityAreaList')->name('state.city.areas');
        Route::get('state/{stateId}/city/{id}/areas/create/', 'AreaController@create')->name('state.city.areas.create');
        Route::post('state/{stateId}/city/{id}/areas/store/', 'AreaController@store')->name('state.city.areas.store');
        Route::get('state/{stateId}/city/{id}/areas/edit/{slug}', 'AreaController@edit')->name('state.area.edit');
        Route::patch('state/{stateId}/city/{id}/areas/update/{areaId}', 'AreaController@update')->name('area.update');
        Route::get('state/{stateId}/city/{id}/areas/status/{slug}', 'AreaController@areaStatus')->name('state.city.area.status');
        Route::delete('state/{stateId}/city/{id}/areas/destroy/{slug}', 'AreaController@destroy')->name('state.city.area.destroy');
    });
});
