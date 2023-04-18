<?php

namespace Modules\Login\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Login\Http\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Users\Entities\MobileVerification;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Carbon\Carbon;
use Modules\Roles\Entities\Role;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Role $Role, CommonRepo $CommonRepo)
    {
        $this->Role = $Role;
        $this->middleware('guest')->except('logout');
        $this->CommonRepo = $CommonRepo;
    }


    public function userLogin(Request $request)
    {
        $rules = [
            'email'    => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required'
        ];
        $messages = [
            'email.required'    => __('Enter email to login.'),
            'password.required' => __('Password is required field'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'messages' => $validator->errors()
            ], 200);
        } else {

            $email = $request->input('email');
            $password = $request->input('password');
            $role = Role::where('slug', 'customer')->first();
            $user = User::withTrashed()->where(['email'  => $email, 'role_id' => $role->id])
                //->where('deleted_at', '>=', now()->subDays(15))
                ->first();
            if ($user && \Hash::check($password, $user->password) && $user->deleted_at >= now()->subDays(15)) {
                $user->restore();
            }
            if ($user) {
                if (!\Hash::check($password, $user->password)) {
                    $errors = 'Please enter valid password';
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                Auth::loginUsingId($user->id);
                if (in_array(Auth::user()->status, [0])) {
                    Auth::logout();
                    $errors = new MessageBag(['message_error' => trans('flash.error.your_account_is_deactivated_contact_to_support')]);
                    return response()->json([
                        'error'    => true,
                        'messages' => $errors,
                        'redirect' => false
                    ], 200);
                }

                if (Auth::guard(get_guard())->user()->hasRole(['admin'])) {
                    $this->guard()->logout();
                    $errors = new MessageBag(['message_error' => trans('This is not admin login panel')]);
                    return response()->json([
                        'error'    => true,
                        'messages' => $errors,
                        'redirect' => false
                    ], 200);
                }

                if (Auth::user()->mobile_verified_at == null) {
                    $redirectTo = route('customer.MobileVerify');

                    $this->CommonRepo->CreateAndSendOtp(auth()->user()->id, true);
                    $this->guard()->logout();
                    $errors = new MessageBag(['message_error' => trans('flash.error.please_complete_mobile_verification')]);
                    return response()->json([
                        'error'    => true,
                        'messages' => $errors,
                        'redirect' =>  $redirectTo
                    ], 200);
                }

                $redirectTo = \Session::get('url.intended')  == ''  ? route(RouteServiceProvider::HOME_ROUTE) :  \Session::get('url.intended');

                $this->guard()->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->getClientIp()
                ]);
                return response()->json([
                    'error'    => false,
                    'messages' => false,
                    'redirect' => $redirectTo ?? url('/')
                ], 200);
            } else {
                $errors = new MessageBag(['message_error' => __('User does not exist')]);
                return response()->json([
                    'error'    => true,
                    'messages' => $errors,
                    'redirect' => false
                ], 200);
            }
        }
    }

    public function userLoginbyMobile(Request $request)
    {
        $rules = [
            'phone'    => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|exists:users',
        ];
        $messages = [
            'phone.required'    => __('Enter mobile no. to login.'),
            'phone.exists'    => __('User does not exists.'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'type'    => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        } else {
            $role = Role::where('slug', 'customer')->first();
            $phone = $request->phone;
            $user = User::withTrashed()->where(['phone' => $phone, 'role_id' => $role->id])->first();

            if ($user) {
                if ($user->deleted_at != null && now() >= $user->deleted_at->addDays(15)) {
                    return response()->json([
                        'type'    => 'error',
                        'error'    => true,
                        'message' => trans('Your account is deleted contact to support'),
                        'url' => false
                    ], 200);
                }

                if ($user->status == 0) {
                    return response()->json([
                        'type'    => 'error',
                        'error'    => true,
                        'message' => trans('flash.error.your_account_is_deactivated_contact_to_support'),
                        'url' => false
                    ], 200);
                }
                if ($user->mobile_verified_at == null) {
                    $redirectTo = route('customer.MobileVerify');
                    $this->CommonRepo->CreateAndSendOtp($user->id, true);
                    return response()->json([
                        'type'    => 'error',
                        'error'    => true,
                        'message' => trans('flash.error.please_complete_mobile_verification'),
                        'url' =>  $redirectTo
                    ], 200);
                }

                $this->CommonRepo->CreateAndSendOtp($user->id, true);
                if ($request->input('redirect')) {
                    session()->put('redirect_url', $request->input('redirect'));
                }
                $reRoute = route('customer.MobileLoginOTPScreen');
                return response()->json([
                    'type'    => 'success',
                    'error'    => false,
                    'message' => trans('flash.success.please_enter_otp_to_login'),
                    'url' => $reRoute ?? url('/')
                ], 200);
            } else {
                $errors = new MessageBag(['message_error' => __('User does not exist')]);
                return response()->json([
                    'type'    => 'error',
                    'error'    => true,
                    'message' => $errors,
                    'url' => false
                ], 200);
            }
        }
    }

    public function resendOTPMobile(Request $request)
    {
        $user_id = session('userId');
        if ($user_id != '') {
            $response = $this->CommonRepo->CreateAndSendOtp($user_id);
            if ($response) {
                $msg = trans('flash.success.otp_resent_successfully');
                $redirectTo = back();
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'success'];
            } else {
                $redirectTo = route('customer.login');
                $msg = "Something went wrong";
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'error'];
            }
        } else {
            $redirectTo = route('customer.login');
            $msg = "Something went wrong";
            $data = ['status_code' => 200, 'message' => $msg,  'type' => 'error', 'url' => $redirectTo];
        }
        return $request->wantsJson()
            ? new JsonResponse($data, 200)
            : redirect($redirectTo)->with('status', $msg);
    }

    public function customerMobileLoginOTPScreen()
    {
        return view('auth.verify_otp_mobile_login');
    }

    public function customerMobileLoginOTPVerify(Request $request)
    {
        $LoggedInUserID = session('userId');
        try {
            session()->forget('redirect_url');
            $user = User::withTrashed()->with(['mobileVerifired'])->where('id', $LoggedInUserID)->first();
            $response = $this->CommonRepo->VerifyOtp($user->id, $user->mobileVerifired->otp_verification_code, $request->mobile_otp);
            if ($response) {

                if ($user->deleted_at != null) {
                    if (now() <= $user->deleted_at->addDays(15)) {
                        $user->restore();
                    } else {
                        $redirectTo = route('customer.login');
                        $msg = "Your account is deleted contact support.";
                        return $request->wantsJson()
                            ? new JsonResponse(['status_code' => 200, 'message' => $msg, 'type' => 'error',  'url' => $redirectTo], 200)
                            : redirect($redirectTo)->with('status', $msg);
                    }
                }
                session()->forget('userId');
                Auth::loginUsingId($user->id);
                $this->guard()->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->getClientIp(),
                ]);
                $msg = trans('flash.success.mobile_otp_verified');
                $redirectTo = \Session::get('url.intended')  == ''  ? route(RouteServiceProvider::HOME_ROUTE) :  \Session::get('url.intended');
                session()->forget('sessionPropertySlug');


                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'success', 'url' => $redirectTo];
                session()->forget('redirect_url');
            } else {
                $msg = trans('flash.error.otp_does_not_matched');
                $redirectTo = route('customer.MobileLoginOTPScreen');
                $data = ['status_code' => 200, 'message' => $msg, 'type' => 'error'];
            }
            // pr($redirectTo);
            return $request->wantsJson()
                ? new JsonResponse($data, 200)
                : redirect($redirectTo)->with('status', $msg);
        } catch (Exception $e) {
            $redirectTo = route('customer.login');
            $msg = "Something went wrong";
            return $request->wantsJson()
                ? new JsonResponse(['status_code' => 200, 'message' => $msg, 'type' => 'error',  'url' => $redirectTo], 200)
                : redirect($redirectTo)->with('status', $msg);
        }
    }


    protected function guard()
    {
        return Auth::guard();
    }


    public function logout(Request $request)
    {
        $redirectRoute = RouteServiceProvider::LOGIN_ROUTE;
        if (auth()->user()->hasRole('customer')) {
            $redirectRoute = RouteServiceProvider::LOGIN_ROUTE;
        } elseif (auth()->user()->hasRole('vendor')) {
            $redirectRoute = RouteServiceProvider::VENDOR_LOGIN_ROUTE;
        } elseif (auth()->user()->hasRole('agent')) {
            $redirectRoute = RouteServiceProvider::AGENT_LOGIN_ROUTE;
        } elseif (auth()->user()->hasRole('company')) {
            $redirectRoute = RouteServiceProvider::COMPANY_LOGIN_ROUTE;
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('success', trans('flash.success.your_account_logged_out_successfully'));
        return redirect()->route($redirectRoute);
    }
    /**
     * Show the application's vendor login form.
     *
     * @return \Illuminate\View\View
     */
    public function showVendorLoginForm()
    {
        return view('auth.vendorLogin');
    }

    public function vendorLoginbyMobile(Request $request)
    {
        $rules = [
            'phone'    => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|exists:users',
        ];
        $messages = [
            'phone.required'    => __('Enter mobile no. to login.'),
            'phone.exists'    => __('User does not exists.'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'type'    => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        } else {
            $phone = $request->input('phone');
            $user = User::where(['phone' => $phone, 'role_id' => 3])->first();
            //dd($user);
            if ($user) {
                if ($user->status == 0) {

                    $msg = trans('flash.error.your_account_is_deactivated_contact_to_support');
                    return $data = [
                        'status_code' => 200,
                        'type'    => 'error',
                        'message' => $msg,
                    ];
                }

                $this->CommonRepo->CreateAndSendOtp($user->id, true);
                $msg =  trans('flash.success.please_enter_otp_to_login');
                $redirectTo = route('vendor.login');
                $data = [
                    'status_code' => 200,
                    'type'    => 'success',
                    'otp_vendor'    => true,
                    'message' =>  $msg,
                ];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo)->with('status', $msg);
            } else {
                $redirectTo = route('vendor.login');
                $msg = 'Credentials does not match our records!';
                $data = ['status_code' => 200, 'message' => $msg, 'type' => 'error'];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo)->with('status', $msg);
            }
        }
    }


    public function vendorMobileLoginOTPVerify(Request $request)
    {
        $LoggedInUserID = session('userId');
        try {
            $user = User::with(['mobileVerifired'])->where('id', $LoggedInUserID)->first();
            $response = $this->CommonRepo->VerifyOtp($user->id, $user->mobileVerifired->otp_verification_code, $request->mobile_otp);
            if ($response) {
                session()->forget('userId');
                Auth::loginUsingId($user->id);
                $this->guard()->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->getClientIp(),
                ]);
                auth()->user()->deactivate_at = NULL;
                auth()->user()->save();
                $msg = trans('flash.success.mobile_otp_verified');
                $redirectTo = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'success', 'url' => $redirectTo];
            } else {
                $msg = trans('flash.error.otp_does_not_matched');
                $redirectTo = route('vendor.login');
                $data = ['status_code' => 200, 'message' => $msg, 'type' => 'error'];
            }
            return $request->wantsJson()
                ? new JsonResponse($data, 200)
                : redirect($redirectTo)->with('status', $msg);
        } catch (Exception $e) {
            $redirectTo = route('vendor.login');
            $msg = "Something went wrong";
            return $request->wantsJson()
                ? new JsonResponse(['status_code' => 200, 'message' => $msg, 'type' => 'error',  'url' => $redirectTo], 200)
                : redirect($redirectTo)->with('status', $msg);
        }
    }

    /**
     * Agent Login Code Start from Here
     * 
     **/
    public function showAgentLoginForm()
    {
        return view('auth.agentLogin');
    }

    public function agentLogin(Request $request)
    {
        $rules = [
            'email'    => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required'
        ];
        $messages = [
            'email.required'    => __('Enter email to login.'),
            'password.required' => __('Password is required field'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'type'    => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            $role = Role::where('slug', 'agent')->first();
            $user = User::withTrashed()->where(['email'  => $email, 'role_id' => $role->id])->first();
            if ($user && \Hash::check($password, $user->password) && $user->deleted_at >= now()->subDays(15)) {
                $user->restore();
            }

            if ($user) {
                if (!\Hash::check($password, $user->password)) {
                    $errors = 'Please enter valid password';
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                Auth::loginUsingId($user->id);
                if (in_array(Auth::user()->status, [0])) {
                    Auth::logout();
                    $errors = trans('flash.error.your_account_is_deactivated_contact_to_support');
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                if (Auth::guard(get_guard())->user()->hasRole(['admin'])) {
                    $this->guard()->logout();
                    $errors = trans('This is not admin login panel');
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                $redirectTo = route(RouteServiceProvider::AGENT_HOME_ROUTE);
                $this->guard()->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->getClientIp()
                ]);

                $msg = "Login successfully";
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'success', 'url' => $redirectTo];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo ?? route('agent.home'))->with('status', $msg);
            } else {
                $errors = new MessageBag(['message_error' => __('Agent does not exist')]);
                $redirectTo = route('agent.login');
                $msg = "Agent does not exist";
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'error'];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo)->with('status', $msg);
            }
        }
    }


    /**
     * Agent Login Code Start from Here
     * 
     **/
    public function showCompanyLoginForm()
    {
        return view('auth.companyLogin');
    }

    public function companyLogin(Request $request)
    {
        $rules = [
            'email'    => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required'
        ];
        $messages = [
            'email.required'    => __('Enter email to login.'),
            'password.required' => __('Password is required field'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'type'    => 'error',
                'message' => $validator->errors()->first()
            ], 200);
        } else {
            $email = $request->input('email');
            $password = $request->input('password');
            $role = Role::where('slug', 'company')->first();
            $user = User::withTrashed()->where(['email'  => $email, 'role_id' => $role->id])->first();
            if ($user && \Hash::check($password, $user->password) && $user->deleted_at >= now()->subDays(15)) {
                $user->restore();
            }

            if ($user) {
                if (!\Hash::check($password, $user->password)) {
                    $errors = 'Please enter valid password';
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                Auth::loginUsingId($user->id);
                if (in_array(Auth::user()->status, [0])) {
                    Auth::logout();
                    $errors = trans('flash.error.your_account_is_deactivated_contact_to_support');
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }
                if (Auth::guard(get_guard())->user()->hasRole(['admin'])) {
                    $this->guard()->logout();
                    $errors = trans('This is not admin login panel');
                    return response()->json([
                        'error'    => true,
                        'type'    => 'error',
                        'message' => $errors,
                        'redirect' => false
                    ], 200);
                }

                $redirectTo = \Session::get('url.intended') == ''  ? route(RouteServiceProvider::COMPANY_HOME_ROUTE) :  \Session::get('url.intended');
                $this->guard()->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $request->getClientIp()
                ]);

                $msg = "Login successfully";
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'success', 'url' => $redirectTo];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo ?? route('company.home'))->with('status', $msg);
            } else {
                $errors = new MessageBag(['message_error' => __('Company does not exist')]);
                $redirectTo = route('company.login');
                $msg = "Company does not exist";
                $data = ['status_code' => 200, 'message' => $msg,  'type' => 'error'];
                return $request->wantsJson()
                    ? new JsonResponse($data, 200)
                    : redirect($redirectTo)->with('status', $msg);
            }
        }
    }
}
