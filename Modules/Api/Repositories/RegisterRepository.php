<?php

namespace Modules\Api\Repositories;

use DB, Mail;
use config, File;
use Validator;
use JWTAuth;
use Carbon\Carbon;
use Auth;
use  Modules\Api\Repositories\RegisterRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\EmailTemplates\Entities\EmailTemplate;
use App\Models\User;
use App\Models\JwtUserTokens;
use Modules\Users\Entities\MobileVerification;
use Modules\Login\Entities\OtpVerification;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Password;



class RegisterRepository implements RegisterRepositoryInterface
{

    public function __construct(Role $Role)
    {
        $this->Role = $Role;
    }
    public function Register($request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i',],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone'       => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'otp' => 'required_if:otpstatus,==,1',
        ]);
        $validator->after(function ($validator) use ($data) {
            $role = $this->Role->where('slug', 'customer')->first();
            if (!empty($role)) {
                $chkEmail = User::where(['email' => $data['email'], 'role_id' => $role->id])->withTrashed()->first();
                if ($chkEmail) {
                    $validator->errors()->add('email', 'Customer email already exists!');
                }
                $chkPhone = User::where(['phone' => $data['phone'], 'role_id' => $role->id])->withTrashed()->first();
                if ($chkPhone) {
                    $validator->errors()->add('phone', 'Customer phone number already exists!');
                }
            }
        });
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if ($request->otpstatus == 1) {
            $role = $this->Role->where('slug', 'customer')->first();
            $clientOtp = $request->otp;
            $otpVerify = OtpVerification::where('email', $request->email)->where('user_role', $role->name)->first();
            if ($clientOtp == $otpVerify->otp) {
                User::$guard_name = 'web';
                $user = User::create([
                    'name' => $data['name'],
                    'email'      => $data['email'],
                    'password'   => Hash::make($data['password']),
                    'status'     => 1,
                    'phone'      => $data['phone'],
                    'role_id' => $role->id,
                    'mobile_verified_at' => Carbon::now()->timestamp
                ]);
                $user->assignRole([$role->id]);
                // $authdata['email'] = $request->email;
                // $authdata['password'] = $request->password;
                // $token =  auth()->attempt($authdata);
                // $user = auth()->user();
                // $user->userToken()->delete();
                // $user->userToken()->create(['token'=>$token]);

                return response()->json([
                    'status_code' => 200,
                    'message' => 'otp is sucessfully verified',
                    // 'access_token' => $token,
                    // 'token_type' => 'Bearer',
                    'data' =>  $data
                ], 200);
            } else {
                return response()->json(['status_code' => 401, 'message' => 'otp does not match with our records'], 401);
            }
        } else {
            $role = $this->Role->where('slug', 'customer')->first();
            OtpVerification::where('email', $request->email)->where('user_role', $role->name)->delete();
            $otpCode =  mt_rand(1000, 9999);
            OtpVerification::create([
                'email' => $request->email,
                'otp' => $otpCode,
                'user_role' => 'customer'
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'otp sent to your mobile number',
                'data' =>  $otpCode
            ], 200);
        }
    }
    public function Login($request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'type' => 'required',
                'phone' => '|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|exists:users|required_if:type,==,mobile',
                'otp' => 'required_if:otp_status,==,1',
                'email'    => 'email|regex:/(.+)@(.+)\.(.+)/i|required_if:type,==,email|exists:users',
                'password' => 'required|min:8'
            ],
            [
                'type.required' => 'login type is required',
                'phone.required_if' => 'Enter mobile number to login',
                'otp.required_if' => 'Enter otp to verify',
                'email.required_if' => 'Enter email to login',
                'password.required_if' => 'Password is required field',
                'email.exists' => 'Email is not exists in our record',
            ]
        );
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if ($request->type == "mobile") {
            return   $this->LoginByMobile($data);
        } else if ($request->type == "email") {
            return   $this->LoginByEmail($data);
        }
    }
    public function LoginByMobile($data)
    {

      
        $phone = $data['phone'];
        $role = $this->Role->where('slug', 'customer')->first();
        $user = User::where('phone', $phone)->where('role_id', $role->id)->first();

        if ($user->status != 1) {
            return response()->json(['status_code' => 401, 'message' => 'Your account is deactivated! Contact support.'], 401);
        }
        if ($user->mobile_verified_at == null) {
            return response()->json(['status_code' => 401, 'message' => 'Please complete mobile verification.'], 401);
        }
        MobileVerification::where('user_id', $user->id)->delete();

        MobileVerification::create([
            'user_id' => $user->id,
            'otp_verification_code' => 4321,
        ]);
        if (isset($data['otp_status'])) {
            if ($data['otp_status'] == 1) {
                return  $this->LoginOtpVerify($data, $user);
            }
        }
    }


    public function LoginOtpVerify($data, $user)
    {
        $detail = MobileVerification::where('user_id', $user->id)->where('otp_verification_code', $data['otp'])->first();
        if (!empty($detail)) {
            if (!$userToken = JWTAuth::fromUser($user)) {
                return response()->json(['status_code' => 401, 'message' => 'invalid_credentials'], 401);
            }

            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip()
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Login successfully',
                'access_token' => $userToken,
                'token_type' => 'Bearer',
                'data' => $user
            ], 200);
        } else {
            return response()->json(['status_code' => 401, 'message' => 'Invalid OTP.'], 401);
        }
    }
    public function LoginByEmail($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $role = $this->Role->where('slug', 'customer')->first();
        $user = User::where('email', $email)->where('role_id', $role->id)->first();
        if ($user) {
            if ($user->status != 1) {
                return response()->json(['status_code' => 401, 'message' => 'your_account_is_deactivated_contact_to_support'], 401);
            }
            if ($user->mobile_verified_at == null) {
                return response()->json(['status_code' => 401, 'message' => 'Please complete mobile verification'], 401);
            }

            $passwordCheck = Hash::check($password, $user->password);
            if ($passwordCheck) {
                return $this->LoginEmailVerify($data, $user);
            } else {
                return response()->json(['status_code' => 401, 'message' => 'your password is not correct'], 401);
            }
        }
    }
    public function LoginEmailVerify($data, $user)
    {
        $userData = array("email" => $data['email'], "password" => $data['password']);
        if (!$jwt_token = JWTAuth::attempt($userData)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($userData)) {
            return response()->json(['status_code' => 401, 'message' => 'invalid_credentials'], 401);
        }
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);
        return response()->json([
            'status_code' => 200,
            'message' => 'Login successfully',
            'access_token' => $jwt_token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 200);
    }

    public function sendResetLinkEmail($request)
    {
        $data =  $request->all();
        $validator = \Validator::make($data, [
            'email'    => ['required', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i',],
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );

        $response['status_code'] = 200;
        return $status === Password::RESET_LINK_SENT ? response()->json([
            'status_code' => 200,
            'message' => 'Password reset link sent successfully.',
        ], 200) : response()->json([
            'status_code' => 401,
            'message' => 'Something went wrong.',
        ], 401);
    }
}
