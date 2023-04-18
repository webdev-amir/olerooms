<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Configuration\Entities\Configuration;
use App\ViewComposers\GlobalDataComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->getCommonRepo();
        
        $this->getFrontendRepo();
    }

    //Use for common functionalities data
    public function getCommonRepo() {
        return $this->app->bind('App\Repositories\Common\CommonRepositoryInterface', 'App\Repositories\Common\CommonRepository');
    }
    
    public function getFrontendRepo() {
        return $this->app->bind('App\Repositories\Frontend\FrontendRepositoryInterface', 'App\Repositories\Frontend\FrontendRepository');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $records = Configuration::all()->toArray();
        $configVariables = array();
        if(!empty($records)) {
            foreach($records as $item) {
                $configVariables[$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
            }
        }
        View::share('configVariables', $configVariables);
        GlobalDataComposer::sendStorageTypesAndSocialLinkData();
        GlobalDataComposer::displayNotificationData();
        GlobalDataComposer::displayCitiesData();
        GlobalDataComposer::displayListSearchData();
    }
}
