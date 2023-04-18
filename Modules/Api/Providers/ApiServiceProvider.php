<?php

namespace Modules\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Api\Repositories\RegisterRepositoryInterface;
use Modules\Api\Repositories\RegisterRepository;
use Modules\Api\Repositories\StaticPages\StaticPagesRepositoryInterface;
use Modules\Api\Repositories\StaticPages\StaticPagesRepository;
use Modules\Api\Repositories\Customer\CustomerRegisterRepositoryInterface;
use Modules\Api\Repositories\Customer\CustomerRegisterRepository;
use Modules\Api\Repositories\Customer\Profile\ProfileRepositoryInterface;
use Modules\Api\Repositories\Customer\Profile\ProfileRepository;
use Modules\Api\Repositories\Customer\Home\HomeRepositoryInterface;
use Modules\Api\Repositories\Customer\Home\HomeRepository;
use Modules\Api\Repositories\Customer\Property\PropertyRepositoryInterface;
use Modules\Api\Repositories\Customer\Property\PropertyRepository;
use Modules\Api\Repositories\Customer\Property\Booking\BookingRepositoryInterface;
use Modules\Api\Repositories\Customer\Property\Booking\BookingRepository;
use Modules\Api\Repositories\Customer\Property\Schedule\ScheduleRepositoryInterface;
use Modules\Api\Repositories\Customer\Property\Schedule\ScheduleRepository;
use Modules\Api\Repositories\Customer\Notification\NotificationRepositoryInterface;
use Modules\Api\Repositories\Customer\Notification\NotificationRepository;


class ApiServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Api';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'api';

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
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
        $this->app->bind(StaticPagesRepositoryInterface::class, StaticPagesRepository::class);
        $this->app->bind(CustomerRegisterRepositoryInterface::class, CustomerRegisterRepository::class);
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
        $this->app->bind(HomeRepositoryInterface::class, HomeRepository::class);
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
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
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
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
