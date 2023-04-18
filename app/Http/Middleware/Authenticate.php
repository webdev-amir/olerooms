<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Providers\RouteServiceProvider;

class Authenticate extends Middleware
{
 /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    { 
        if(auth()->user() && !auth()->user()->hasRole('admin')){
            if(auth()->user() && auth()->user()->status==0){
                if(auth()->user()->hasRole('customer')){
                    $redirctRoute = RouteServiceProvider::CUSTOMER_LOGIN_ROUTE;
                }else if(auth()->user()->hasRole('vendor')){
                    $redirctRoute = RouteServiceProvider::VENDOR_LOGIN_ROUTE;
                }else if(auth()->user()->hasRole('agent')){
                    $redirctRoute = RouteServiceProvider::AGENT_LOGIN_ROUTE;
                }else if(auth()->user()->hasRole('company')){
                    $redirctRoute = RouteServiceProvider::COMPANY_LOGIN_ROUTE;
                }else{
                    $redirctRoute = RouteServiceProvider::LOGIN_ROUTE;
                }
                 $this->auth->logout();
                 return redirect()->route($redirctRoute)->with('error',trans('flash.error.your_account_is_deactivated_contact_to_support'));
            }
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $guards[0] = get_guard();
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {  
        if(get_guard() != 'admin'){
            if(\Request::segment(1) == 'admin'){
                 return route(RouteServiceProvider::ADMIN_LOGIN_ROUTE);
            }
            if (!$request->expectsJson()) {
                if(request()->segment(1) == 'customer'){
                    return route(RouteServiceProvider::CUSTOMER_LOGIN_ROUTE);
                }
                if(request()->segment(1) == 'owner'){
                    return route(RouteServiceProvider::VENDOR_LOGIN_ROUTE);
                }
                if(request()->segment(1) == 'agent'){
                    return route(RouteServiceProvider::AGENT_LOGIN_ROUTE);
                }
                if(request()->segment(1) == 'company'){
                    return route(RouteServiceProvider::COMPANY_LOGIN_ROUTE);
                }
                return route(RouteServiceProvider::LOGIN_ROUTE);
            }
        }
    }
}
