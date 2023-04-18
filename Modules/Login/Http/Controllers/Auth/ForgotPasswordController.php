<?php

namespace Modules\Login\Http\Controllers\Auth;

use Mail,Password;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\Login\Http\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

         /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $response = 'passwords.user';
        $user = User::where('email',$request->only('email'))->whereHas('roles', function (Builder $q) use ($request) {
            if ($request->get('rolename')) {
                $q->where('slug', $request->get('rolename'));
            }
        })->first();
        if($user && $user->hasRole($request->get('rolename'))){
            $rolename = $request->get('rolename');
            $token = Password::getRepository()->create($user);
            $this->changeresetLinkToken($user,$token);
            $resetlink =  route($rolename.'.password.reset', $token).'?email='.$user->email;
            $emailtemplate = EmailTemplate::where('slug','forgot-password')->first();
            $subject = $emailtemplate->subject;
            $body = $emailtemplate->body;
            $body = str_replace('[username]', $user->name, $body);
            $body = str_replace('[resetlink]', '<a href="'.$resetlink.'">'.$resetlink.'</a>', $body);
            $jobData = [
                'content' => $body,
                'user' => $user,
                'to' => $user->email,
                'subject' => $subject
            ];
            $response = 'passwords.sent';
            dispatch(new \App\Jobs\SendEmailJob($jobData));   
        }
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function changeresetLinkToken($user,$token)
    {
          $user->forceFill([
                'remember_token' => $token,
            ])->save();
        return $user->remember_token;
    }
}
