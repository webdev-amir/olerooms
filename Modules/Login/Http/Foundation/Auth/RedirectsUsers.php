<?php

namespace Modules\Login\Http\Foundation\Auth;
use App\Providers\RouteServiceProvider;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        if(auth()->user()){
            if (auth()->user()->hasRole('customer')) {
                $this->redirectTo = RouteServiceProvider::HOME;
            } elseif (auth()->user()->hasRole('vendor')) {
                $this->redirectTo = RouteServiceProvider::VENDOR_DASH;
            }elseif (auth()->user()->hasRole('agent')) {
                $this->redirectTo = RouteServiceProvider::AGENT_DASH;
            }elseif (auth()->user()->hasRole('company')) {
                $this->redirectTo = RouteServiceProvider::COMPANY_DASH;
            }  
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : 'customer/dashboard';
    }
}
