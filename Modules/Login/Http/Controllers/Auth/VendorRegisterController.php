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
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;

class VendorRegisterController extends Controller
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
    protected $redirectTo = RouteServiceProvider::VENDOR_HOME_ROUTE;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Role $Role, EmailNotificationsRepo $EmailNotificationsRepo)
    {
        $this->Role = $Role;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;

        $this->middleware('guest');
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
                'regex:/(.+)@(.+)\.(.+)/i'
                //'unique:users',
            ],
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'term'        => ['required'],
        ]);
        $validator->after(function ($validator) use ($data) {
            $role = $this->Role->where('slug', 'vendor')->first();
            if (!empty($role)) {
                $chkEmail = User::where(['email' => $data['email'], 'role_id' => $role->id])->where('deleted_at', NULL)->first();
                if ($chkEmail) {
                    $validator->errors()->add('email', 'Vendor email already exists!');
                }
                $chkPhone = User::where(['phone' => $data['phone'], 'role_id' => $role->id])->where('deleted_at', NULL)->first();
                if ($chkPhone) {
                    $validator->errors()->add('email', 'Vendor phone number already exists!');
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $role = $this->Role->where('slug', 'vendor')->first();
        User::$guard_name = 'web';
        if ($role) {
            $user->assignRole([$role->id]);
        }
        return $user;
    } 

    public function userRegister(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = \App\Models\User::create([
            'name' => $request->input('name'),
            'email'      => $request->input('email'),
            'password'   => Hash::make(rand()),
            'status'     => 1,
            'phone'      => $request->input('phone'),
            'email_verified_at' => now(),
            'mobile_verified_at' => now(),
        ]);
        event(new Registered($user));
        $role = $this->Role->where('slug', 'vendor')->first();
        User::$guard_name = 'web';
        $user->role_id = $role->id;
        $user->save();
        $user->assignRole([$role->id]);
        Auth::loginUsingId($user->id);
        $this->EmailNotificationsRepo->sendWelcomeEmailForVendor($user);
        $this->EmailNotificationsRepo->sendVendorRegisterNotifyMailToAdmin($user);
        $redirectTo = $this->redirectPath();

        $msg = trans('flash.success.your_account_has_been_successfully_registered');
        return $request->wantsJson()
            ? new JsonResponse(['status_code' => 200, 'message' => $msg, 'type' => 'success', 'reset' => 'true', 'url' => route($redirectTo)], 200)
            : redirect($redirectTo)->with('status', $msg);
    }
}
