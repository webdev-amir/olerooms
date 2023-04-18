<?php

namespace Modules\Login\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Modules\Login\Http\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Roles\Entities\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Modules\Users\Entities\MobileVerification;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME_ROUTE;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Role $Role,
        CommonRepo $CommonRepo,
        EmailNotificationsRepository $EmailNotificationsRepo
    ) {
        $this->Role = $Role;
        $this->middleware('guest');
        $this->CommonRepo = $CommonRepo;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:100'
            ],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/(.+)@(.+)\.(.+)/i',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                // 'regex:/[0-9]/',      // must contain at least one digit
                // 'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'term'        => ['required'],
        ]);

        $validator->after(function ($validator) use ($data) {
            $role = $this->Role->where('slug', 'customer')->first();
            if (!empty($role)) {
                $chkEmail = User::where(['email' => $data['email'], 'role_id' => $role->id])->where('deleted_at', NULL)->first();
                if ($chkEmail) {
                    $validator->errors()->add('email', 'Customer email already exists!');
                }
                $chkPhone = User::where(['phone' => $data['phone'], 'role_id' => $role->id])->where('deleted_at', NULL)->first();
                if ($chkPhone) {
                    $validator->errors()->add('phone', 'Customer phone number already exists!');
                }
            }
        });
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        User::$guard_name = 'web';
        $role = $this->Role->where('slug', 'customer')->first();
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'status'     => 1,
            'phone'      => $data['phone'],
            'role_id' => $role->id,
        ]);
        $this->CommonRepo->CreateAndSendOtp($user->id, true);
        if ($role) {
            $user->assignRole([$role->id]);
            $this->EmailNotificationsRepo->sendWelcomeEmailForUser($user, $data['password']);
        }
        return $user;
    }


    public function getcustomerMobileVerify()
    {
        return view('auth.verify_otp_mobile_register');
    }


    public function customerMobileVerifyOTP(Request $request)
    {
        $registerdUserID = session('userId');
        try {
            $user = User::with(['mobileVerifired'])->where('id', $registerdUserID)->first();
            $response = $this->CommonRepo->VerifyOtp($user->id, $user->mobileVerifired->otp_verification_code, $request->mobile_otp);
            if ($response) {
                $user->update([
                    'mobile_verified_at' => now()
                ]);
                Auth::loginUsingId($user->id);
                session()->forget('userId');
                $msg = trans('flash.success.mobile_otp_verified');
                $redirectTo = route(RouteServiceProvider::HOME_ROUTE);
                // $redirectTo = route('login');
                $data = ['status_code' => 200, 'message' => $msg, 'type' => 'success',  'url' => $redirectTo];
            } else {
                $msg = trans('flash.error.otp_does_not_matched');
                $redirectTo = route('customer.MobileVerify');
                $data = ['status_code' => 200, 'message' => $msg, 'type' => 'error'];
            }
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
}
