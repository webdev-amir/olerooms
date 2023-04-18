<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class APIAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->getApiRepo();
    }

     //Use for Api repo
    public function getApiRepo() {
        return $this->app->bind('App\Repositories\Api\ApiRepositoryInterface','App\Repositories\Api\ApiRepository');
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
