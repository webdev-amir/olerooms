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
Route::prefix('admin')->group( function() {
	Route::middleware(['web'])->group(function () {
		Route::resource('contactus', 'ContactusController')->except([
		   'show','edit','create','store','update',
		]);
	 });
});

Route::group(
	[
		//'prefix' => LaravelLocalization::setLocale(),
		//'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
	], 
function()
{
	//Route translate middleware
	Route::get('contactus', 'ContactusController@create')->name('contactus.create');
	Route::post('contactus', 'ContactusController@store')->name('contactus.store');
});
