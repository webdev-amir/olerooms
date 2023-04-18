<?php

namespace Modules\Login\Http\Foundation\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Cron\Repositories\CronRepository;
use Modules\Login\Http\Foundation\Auth\RedirectsUsers;

trait VerifiesEmails
{
    use RedirectsUsers;

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $userInfo = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('id'), (string) $userInfo->getKey())) {
            throw new AuthorizationException;
        }

        if (!hash_equals((string) $request->get('hash'), sha1($userInfo->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($userInfo->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        if ($userInfo->markEmailAsVerified()) {
            event(new Verified($userInfo));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }
        
        if(auth()->user()){
            \Session::flash('success', 'Email has verified successfully');
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath())->with('verified', true);
        }
        \Session::flash('success', 'Email has verified. Please login to see your account');
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('login')->with('verified', true);
    }

    /**
     * The user has been verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function verified(Request $request)
    {
        //
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }
        //$request->user()->sendEmailVerificationNotification();
        $this->EmailNotificationsRepo->sendVerifyEmail($request,$request->user());

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : back()->with('resent', true);
    }
}
