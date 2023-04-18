<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth,Route;
use App\Providers\RouteServiceProvider;

class CheckIsUserCompleteProfileVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    { 
        if(Auth::user() && !Auth::user()->is_profileVerifired()){
            if(Route::currentRouteName() != RouteServiceProvider::COMPLETE_PROFILE_VERIFICATION_ROUTE && Route::currentRouteName() != RouteServiceProvider::COMPLETE_PROFILE_VERIFICATION_POST_ROUTE){
                if($request->ajax()){
                  /*  $response['message'] = 'Sorry! Unauthorized Access';
                    $response['type'] = 'error';
                    $response['status_code'] = 400;
                    return $response;*/
                }
                return redirect()->route(RouteServiceProvider::COMPLETE_PROFILE_VERIFICATION_ROUTE);
            }
       }else{
            /*if(Auth::user() && !auth()->user()->is_profileVerifiredApproved()){
                if(Route::currentRouteName() == 'vendor.dashboard.myprofile'){
                    return redirect()->route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                }
            }*/
            if(Route::currentRouteName() == RouteServiceProvider::COMPLETE_PROFILE_VERIFICATION_ROUTE && Route::currentRouteName() != RouteServiceProvider::COMPLETE_PROFILE_VERIFICATION_POST_ROUTE){
                $request->session()->flash('success', 'Profile already completed');
                return redirect()->route(RouteServiceProvider::VENDOR_HOME_ROUTE);
            }
       }
        return $next($request);
    }
}
