<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/customer/myprofile';
    public const ADMIN = '/admin/dashboard';
    public const VENDOR_DASH = '/owner/dashboard';
    public const AGENT_DASH = '/agent/dashboard';
    public const COMPANY_DASH = '/company/dashboard';
    public const LOGIN = '/customer/login';
    public const ADMIN_LOGIN = '/admin/login';
    public const ADMIN_DASHBOARD_ROUTE = 'backend.dashboard';
    public const HOME_ROUTE = 'customer.dashboard.myprofile';
    public const AGENT_HOME_ROUTE = 'agent.dashboard.myprofile';
    public const COMPANY_HOME_ROUTE = 'company.dashboard.myprofile';
    public const VENDOR_HOME_ROUTE = 'vendor.dashboard';
    public const OTPVERIFICATION_ROUTE = 'users.phoneVerification';
    public const OTPVERIFICATION_SUCCESS_ROUTE = 'users.phoneVerificationSuccess';
    public const COMPLETE_PROFILE_VERIFICATION_ROUTE = 'vendor.completeProfileVerification';
    public const LOGIN_ROUTE = 'customer.login';
    public const CUSTOMER_LOGIN_ROUTE = 'customer.login';
    public const COMPLETE_PROFILE_VERIFICATION_POST_ROUTE = 'vendor.submitCompleteProfileVerification';
    public const VENDOR_LOGIN_ROUTE = 'vendor.login';
    public const AGENT_LOGIN_ROUTE = 'agent.login';
    public const COMPANY_LOGIN_ROUTE = 'company.login';
    public const ADMIN_LOGIN_ROUTE = 'admin.login';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
