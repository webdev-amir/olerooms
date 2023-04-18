<?php

namespace Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Booking';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'booking';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->FrontBookingRepo();
        $this->BookingRepo();
        $this->CancelBookingRepo();
        $this->CancelScheduleVisitRepo();
    }


    //Use for FrontBookingRepo Repository Interface
    public function FrontBookingRepo()
    {
        return $this->app->bind('Modules\Booking\Repositories\Frontend\BookingRepositoryInterface', 'Modules\Booking\Repositories\Frontend\BookingRepository');
    }

    //Use for Backend BookingRepo Repository Interface
    public function BookingRepo()
    {
        return $this->app->bind('Modules\Booking\Repositories\Backend\BookingRepositoryInterface', 'Modules\Booking\Repositories\Backend\BookingRepository');
    }

    //Use for Backend CancelBookingRepo Repository Interface
    public function CancelBookingRepo()
    {
        return $this->app->bind('Modules\Booking\Repositories\Backend\CancelBookingRepositoryInterface', 'Modules\Booking\Repositories\Backend\CancelBookingRepository');
    }

    //Use for Backend CancelScheduleVisitRepo Repository Interface
    public function CancelScheduleVisitRepo()
    {
        return $this->app->bind('Modules\Booking\Repositories\Backend\CancelScheduleVisitRepositoryInterface', 'Modules\Booking\Repositories\Backend\CancelScheduleVisitRepository');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
