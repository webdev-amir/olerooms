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
        'prefix' => 'customer/schedulevisit',
        'middleware' => ['web','auth']
    ],
    function () {
        Route::any('schedulevisit/{slug}', 'Frontend\ScheduleVisitController@index')->name('schedulevisit.index');
        Route::post('/store-visit', 'Frontend\ScheduleVisitController@storeScheduleVisit')->name('schedulevisit.store');
        Route::post('/visit-update', 'Frontend\ScheduleVisitController@updateScheduleVisit')->name('schedulevisit.update');
        Route::any('/success/{slug}', 'Frontend\ScheduleVisitController@success')->name('schedulevisit.success');
        Route::post('/visit-delete', 'Frontend\ScheduleVisitController@deleteVisit')->name('schedulevisit.delete');
});

Route::group(
    [
        'as' => 'company.',
        'prefix' => 'company/schedulevisit',
        'middleware' => ['web','auth']
    ],
    function () {
        Route::any('schedulevisit/{slug}', 'Frontend\CompanyScheduleVisitController@index')->name('schedulevisit.index');
        Route::post('/store-visit', 'Frontend\CompanyScheduleVisitController@storeScheduleVisit')->name('schedulevisit.store');
        Route::post('/visit-update', 'Frontend\CompanyScheduleVisitController@updateScheduleVisit')->name('schedulevisit.update');
        Route::any('/success/{slug}', 'Frontend\CompanyScheduleVisitController@success')->name('schedulevisit.success');
        Route::post('/visit-delete', 'Frontend\CompanyScheduleVisitController@deleteVisit')->name('schedulevisit.delete');
});

Route::prefix('admin')->group(function () {
    Route::middleware(['web', 'auth'])->group(function () {
        // Bookings
        Route::resource('schedulevisit', 'ScheduleVisitController', ['names' => 'adminschedulevisit'])->except([
            'create', 'edit', 'store','show'
        ]);
        Route::get('schedulevisit/{visit_slug}', 'ScheduleVisitController@schedulevisitDetails')->name('adminschedulevisit.details');
        Route::post('schedulevisit', 'ScheduleVisitController@index')->name('adminschedulevisit.ajax');
    });
});
