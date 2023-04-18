<?php

namespace App\ViewComposers;

use DB, Config;
use Illuminate\Support\Facades\Auth;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Notifications\Entities\Notifications;
use Modules\Configuration\Entities\Configuration;
use Modules\City\Entities\City;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class GlobalDataComposer
{
    
    public static function sendStorageTypesAndSocialLinkData()
    {
        view()->composer(['includes.footer-storage-social', 'includes.header'], function ($view) {
            $limit = 6;
            $storageTyData = PropertyType::where('status', 1)->orderBy('sortby','asc')->limit($limit)->get();
            $view->with(compact('storageTyData'));
        });

        view()->composer(['includes.footer'], function ($view) {
            $propertyTypes = \Cache::remember('propertyTypes', 3000, function () {
                return PropertyType::where('status', 1)->orderBy('sortby','asc')->limit(6)->get();
            });
            $socialLinkData = \Cache::remember('socialLinkData', 3000, function () {
                $records = Configuration::whereIn('slug', ['facebook', 'instagram', 'twitter', 'pinterest', 'adminemail', 'admincontact','admincontact2','whatsapp','linkedin','youtube'])->get();
                $socialLinkData = NULL;
                if (!empty($records)) {
                    foreach ($records as $item) {
                        $socialLinkData[$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
                    }
                }
                return $socialLinkData;
            });
            $view->with(compact('propertyTypes','socialLinkData'));
        });
    }

    public static function displayNotificationData()
    {
            view()->composer(['includes.notification','propertyownerdashboard::frontend.notification.notification-dashboard'], function ($view) {
            $limit = 6;
            $notoficationData = Notifications::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->limit($limit)->get();
            $notoficationCnt = count(Notifications::where('read_at', null)->where('user_id', Auth::id())->limit($limit)->get());
            $view->with(compact('notoficationData', 'notoficationCnt'));
        });
    }
	public static function displayCitiesData()
    {
        view()->composer(['frontend.includes.city'], function ($view) {
            $cityData = City::where('status', 1)->orderBy('status','asc')->get();
            $view->with(compact('cityData'));
        });
		
		view()->composer(['frontend.includes.occupancy'], function ($view) {
			$occupancyData = array('single'=>'Single', 'double'=>'Double', 'triple'=>'Triple', 'quadruple'=>'Quadruple');
            $view->with(compact('occupancyData'));
        });
		
		view()->composer(['frontend.includes.flatbhk'], function ($view) {
			$bhkData = array('1'=>'1BHK', '2'=>'2BHK', '3'=>'3BHK', '4'=>'4BHK','5'=>'5BHK','6'=>'6BHK','7'=>'7BHK');
            $view->with(compact('bhkData'));
        });
    }
	
	public static function displayListSearchData()
    {
        view()->composer(['frontend.includes.listsearch.city'], function ($view) {
            $cityData = City::where('status', 1)->orderBy('status','asc')->get();
            $view->with(compact('cityData'));
        });
		
		view()->composer(['frontend.includes.listsearch.occupancy'], function ($view) {
			$occupancyData = array('single'=>'Single', 'double'=>'Double', 'triple'=>'Triple', 'quadruple'=>'Quadruple');
            $view->with(compact('occupancyData'));
        });
		
		view()->composer(['frontend.includes.listsearch.flatbhk'], function ($view) {
			$bhkData = array('1'=>'1BHK', '2'=>'2BHK', '3'=>'3BHK', '4'=>'4BHK','5'=>'5BHK','6'=>'6BHK','7'=>'7BHK');
            $view->with(compact('bhkData'));
        });
    }
	
}
