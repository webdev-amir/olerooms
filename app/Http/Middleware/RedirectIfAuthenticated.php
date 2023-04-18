<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(auth()->user() && auth()->user()->hasRole('customer')){
                    return redirect(route(RouteServiceProvider::HOME_ROUTE))->with('error',trans('flash.error.you_have_not_permission_for_this_page_access'));
                 }elseif(auth()->user() && auth()->user()->hasRole('vendor')){
                    return redirect(route(RouteServiceProvider::VENDOR_HOME_ROUTE))->with('error',trans('flash.error.you_have_not_permission_for_this_page_access'));
                 }elseif(auth()->user() && auth()->user()->hasRole('agent')){
                    return redirect(route(RouteServiceProvider::AGENT_HOME_ROUTE))->with('error',trans('flash.error.you_have_not_permission_for_this_page_access'));
                 }elseif(auth()->user() && auth()->user()->hasRole('company')){
                    return redirect(route(RouteServiceProvider::COMPANY_HOME_ROUTE))->with('error',trans('flash.error.you_have_not_permission_for_this_page_access'));
                 }
                 return redirect('/')->with('error',trans('flash.error.you_have_not_permission_for_this_page_access'));
            }
        }
        return $next($request);
    }
}
