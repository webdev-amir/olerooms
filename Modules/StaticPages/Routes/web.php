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
	Route::middleware(['web','auth'])->group(function () {
		Route::resource('staticpages', 'StaticPagesController')->except([
		   'show','destroy'
		]);
		//Upload Images By Ajax
		Route::post('staticpages/media/upload', 'StaticPagesController@saveMedia')->name('staticpages.mediaStore');
    });
});

Route::group(
	[
		//'prefix' => LaravelLocalization::setLocale(),
		//'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
	], 
function()
{
	//Route translate middleware
	Route::get('faq', 'Frontend\FrontStaticPageController@faq')->name('frontend.faq');
	Route::get('about-ole-rooms', 'Frontend\FrontStaticPageController@aboutUs')->name('frontend.aboutUs');
	Route::get('life-at-ole-rooms', 'Frontend\FrontStaticPageController@lifeatoleRooms')->name('frontend.lifeatoleRooms');
	Route::get('newsupdates', 'Frontend\FrontStaticPageController@News')->name('frontend.news');
	Route::get('newsupdates/{slug}', 'Frontend\FrontStaticPageController@NewsDetail')->name('newsupdate.list');
	Route::get('newsupdates/{type}', 'Frontend\FrontStaticPageController@News')->name('news.type');
	Route::get('{page}', 'Frontend\FrontStaticPageController@show')->name('pages.show');
});