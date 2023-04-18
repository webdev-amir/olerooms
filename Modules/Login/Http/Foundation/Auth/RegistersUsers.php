<?php

namespace Modules\Login\Http\Foundation\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\EmailTemplates\Entities\EmailTemplate;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        $msg = trans('flash.success.account_verify_mobile_otp_success_message');
        // $redirectTo = route($this->redirectPath());
        $redirectTo = route('customer.MobileVerify');

        return $request->wantsJson()
            ? new JsonResponse(['status_code' => 200, 'message' => $msg, 'type' => 'success', 'reset' => 'true', 'url' => $redirectTo], 200)
            : redirect($redirectTo)->with('status', $msg);    

        //$this->guard()->login($user);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if($user->hasRole('customer')){
            $verifyRoute = 'verification.verify';
        }elseif($user->hasRole('agent')){
            $verifyRoute = 'agent.verification.verify';
        }elseif($user->hasRole('company')){
            $verifyRoute = 'company.verification.verify';
        }else{
            $verifyRoute = 'verification.verify';
        }
        $verifyUrl = \URL::temporarySignedRoute($verifyRoute,
        \Illuminate\Support\Carbon::now()->addMinutes(\Illuminate\Support\Facades 
        \Config::get('auth.verification.expire', 60)),
        [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
        );
        $emailtemplate = EmailTemplate::where('slug', 'activate-user')->first();
        $search_fields = ['[username]','[activationlink]','#'];
        $replace_data = [$user->name, $verifyUrl ,$verifyUrl];
        $body = str_replace($search_fields, $replace_data, $emailtemplate->body);
        $jobData = [
            'content' => $body,
            'to' => $user->email,
            'subject' => $emailtemplate->subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }
}
