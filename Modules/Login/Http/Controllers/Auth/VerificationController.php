<?php

namespace Modules\Login\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Modules\Login\Http\Foundation\Auth\VerifiesEmails;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EmailNotificationsRepository $EmailNotificationsRepo)
    {
        // $this->middleware('auth');
        $this->middleware(['auth'], ['except' => ['verify']]);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
    }
}
