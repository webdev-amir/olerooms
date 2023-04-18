<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Providers\RouteServiceProvider;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    { 
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
                if(auth()->user()->hasRole('customer')){
                    $resendRoute = 'verification.notice';
                }elseif(auth()->user()->hasRole('agent')){
                    $resendRoute = 'agent.verification.notice';
                }elseif(auth()->user()->hasRole('company')){
                    $resendRoute = 'company.verification.notice';
                }else{
                    $resendRoute = 'verification.notice';
                }
            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::guest(URL::route($redirectToRoute ?: $resendRoute));
        }

        return $next($request);
    }
}
