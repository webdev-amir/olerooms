<?php

namespace Modules\Api\Repositories\Customer;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\Users\Entities\MobileVerification;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository;
use Modules\Login\Entities\OtpVerification;
use Modules\Roles\Entities\Role;


class CustomerRegisterRepository implements CustomerRegisterRepositoryInterface
{
    public $Role;
    public $OtpVerification;
    public $EmailNotifications;

    public function __construct(Role $Role, EmailNotificationsRepository $EmailNotifications, OtpVerification $OtpVerification)
    {
        $this->Role = $Role;
        $this->OtpVerification = $OtpVerification;
        $this->EmailNotifications = $EmailNotifications;
    }

    public function register($request)
    {

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name'     => ['required', 'string', 'max:100'],
                'password' => ['required', 'string', 'min:8'],
                'phone'    => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'otp'      => 'required_if:isotp,==,1',
                'email'    => ['required', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i',],
            ],
            [
                'otp.required_if' => 'Otp is required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        $role = $this->Role->where('slug', 'customer')->first();
        if (!empty($role)) {
            $chkEmail = User::where(['email' => $data['email'], 'role_id' => $role->id])->withTrashed()->first();
            if ($chkEmail) {
                $validator->errors()->add('email', 'Customer email already exists!');
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $chkPhone = User::where(['phone' => $data['phone'], 'role_id' => $role->id])->withTrashed()->first();
            if ($chkPhone) {
                $validator->errors()->add('phone', 'Customer phone number already exists!');
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
        }
        if ($request->isotp == 1) {
            User::$guard_name = 'web';
            $role = $this->Role->where('slug', 'customer')->first();
            if ($role) {
                $clientOtp = $request->otp;
                $otpVerify = $this->OtpVerification->where('email', $request->email)
                    ->where('user_role', $role->name)->first();
                if ($otpVerify) {
                    if ($clientOtp == $otpVerify->otp) {
                        User::$guard_name = 'web';

                        $user = User::create([
                            'name' => $data['name'],
                            'email'      => $data['email'],
                            'password'   => Hash::make($data['password']),
                            'status'     => 1,
                            'phone'      => $data['phone'],
                            'role_id' => $role->id,
                            'mobile_verified_at' => now()
                        ]);

                        $user->assignRole([$role->id]);

                        if ($request->device_token) {

                            $userExists = User::where('device_token', $request->device_token)->first();
                            if ($userExists) {
                                $userExists->update(array("device_token" => null));
                            }
                            $user->update(array("device_token" => $request->device_token));
                        }
                        $this->EmailNotifications->sendCreateUserEmail($request, $user);
                        $response['status_code'] = 200;
                        $response['message'] = 'User registered successfully';
                        $response['user'] = $user;
                        auth()->login($user);
                        if (!$token = JWTAuth::fromUser($user)) {
                            $response['status_code'] = 401;
                            $response['message'] = 'Unauthorized';
                        }
                        $response =  $this->createNewTokenForRegisterLogin($token);
                        return $response;
                    } else {
                        $response['status_code'] = 401;
                        $response['message'] = 'OTP does not match with our records';
                    }
                } else {
                    $response['status_code'] = 401;
                    $response['message'] = 'OTP not verified';
                }
            } else {
                $response['status_code'] = 401;
                $response['message'] = 'Customer Role not available please contact to oleroom support';
                $response['data'] = [];
            }
        } else {
            $role = $this->Role->where('slug', 'customer')->first();
            $otpCode =  1234;
            if ($role) {
                $this->OtpVerification->where('email', $request->email)->where('user_role', $role->name)->delete();
                $this->OtpVerification->create([
                    'email' => $request->email,
                    'otp' => $otpCode,
                    'user_role' => 'customer'
                ]);
                $response['status_code'] = 200;
                $response['message'] = 'OTP sent to your mobile number';
                $response['otp'] = $otpCode;
                $response['data'] = [];
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Customer Role not available please contact to oleroom support';
                $response['otp'] = $otpCode;
            }
        }

        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function login($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|in:mobile,email'
            ],
            [
                'type.required' => 'login type is required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if ($request->type == "mobile") {
            return   $this->loginByMobileNumber($request);
        } else if ($request->type == "email") {
            return $this->loginByEmail($request);
        }
    }

    public function loginByMobileNumber($request)
    {
        $data = $request->all();
        $validator = Validator::make(
            $data,
            [
                'otp'       => 'required_if:isotp,==,1',
                'phone'     => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'device_token' => 'required_if:isotp,==,1'
            ],
            [
                'phone.required_if' => 'Enter mobile number to login',
                'otp.required_if' => 'Enter otp to verify',
                'phone.min' => 'The phone must be at least 10 digits"',
            ]
        );
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $role = $this->Role->where('slug', 'customer')->first();
            $user = User::withTrashed()->where('phone', $request->get('phone'))->where('role_id', $role->id)->first();
            if ($user) {
                if ($request->get('isotp') == 1) {
                    return $this->OtpVerifyAndLogin($request, $user);
                } else {
                    MobileVerification::where('user_id', $user->id)->delete();
                    MobileVerification::create([
                        'user_id' => $user->id,
                        'otp_verification_code' => 1234,
                    ]);
                    $response['status_code'] = 200;
                    $response['message'] = 'OTP sent to your mobile number';
                    $response['otp'] = 1234;
                    $response['data'] = [];
                }
            } else {
                $response['status_code'] = 401;
                $response['message'] = 'User not exists with this mobile number';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function OtpVerifyAndLogin($request, $user)
    {
        try {
            $verifyRecord = MobileVerification::where('user_id', $user->id)
                ->where('otp_verification_code', $request['otp'])->first();
            if ($verifyRecord) {
                if ($user->deleted_at != null) {
                    if (now() <= $user->deleted_at->addDays(15)) {
                        $user->restore();
                    }
                }
                if ($user->status == 0) {
                    $response['status_code'] = 401;
                    $response['message'] = 'Your account is deactivate please contact to Support';
                }
                auth()->login($user);

                if (!$token = JWTAuth::fromUser($user)) {
                    $response['status_code'] = 401;
                    $response['message'] = 'Unauthorized';
                }
                if ($request->device_token) {
                    $deviceToken = $request->device_token;
                }

                $response =  $this->createNewTokenForLogin($token, $deviceToken);
                return $response;
            } else {
                $response['status_code'] = 401;
                $response['message'] = 'Your account is deactivate please contact to Support';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    protected function createNewTokenForLogin($token, $deviceToken)
    {

        $user = auth()->user();



        if ($deviceToken) {
            $user->update(array("device_token" => null));
            $userExists = User::where('device_token', $deviceToken)->first();
            if ($userExists) {
                $userExists->update(array("device_token" => null));
            }
            $user->update(array("device_token" => $deviceToken));
        }
        $user['user_image_path'] = auth()->user()->ThumbPicturePath;
        $user->userToken()->delete();
        $user->userToken()->create(['token' => $token]);
        return response()->json([
            'status_code' => 200,
            'message' => 'Login successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 200);
    }
    
    protected function createNewTokenForRegisterLogin($token)
    {

        $user = auth()->user();
        $user['user_image_path'] = auth()->user()->ThumbPicturePath;
        $user->userToken()->delete();
        $user->userToken()->create(['token' => $token]);
        return response()->json([
            'status_code' => 200,
            'message' => 'Register successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ], 200);
    }

    public function loginByEmail($request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'password'  => 'required|min:8',
            'email'     => 'email|regex:/(.+)@(.+)\.(.+)/i|required_if:type,==,email',
            'type'      => 'required|in:email',
            'device_token' => 'required'

        ], [
            'type.required' => 'login type is required',
            'email.required_if' => 'Enter email to login',
            'password.required_if' => 'Password is required field'
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $email = $data['email'];
            $password = $data['password'];
            $role = $this->Role->where('slug', 'customer')->first();
            $user = User::withTrashed()->where(['email'  => $email, 'role_id' => $role->id])->first();

            if ($user) {
                if ($user->status != 1) {
                    $response['status_code'] = 401;
                    $response['message'] = 'your_account_is_deactivated_contact_to_support';
                }
                if ($user->mobile_verified_at == null) {
                    $response['status_code'] = 401;
                    $response['message'] = 'please_complete_mobile_verification';
                }
                if ($user->deleted_at != null) {
                    if (now() <= $user->deleted_at->addDays(15)) {
                        $user->restore();
                    }
                }
                $passwordCheck = Hash::check($password, $user->password);

                if ($passwordCheck) {
                    $user->restore();
                    return $this->LoginEmailVerify($data, $user);
                } else {
                    $response['status_code'] = 200;
                    $response['message'] = 'your password is not correct';
                }
            } else {
                $response['status_code'] = 401;
                $response['message'] = 'user not found';
            }
        } catch (\Exception $th) {

            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function LoginEmailVerify($data, $user)
    {

        $userData = array("email" => $data['email'], "password" => $data['password']);
        if (!$token = JWTAuth::attempt($userData)) {
            $response['status_code'] = 401;
            $response['message'] = 'invalid_credentials';
            return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        }
        $deviceToken = $data['device_token'];

        $response =  $this->createNewTokenForLogin($token, $deviceToken);
        return $response;
    }

    public function logout($request)
    {
        auth()->user()->userToken()->delete();
        auth()->logout();
        $response['status_code'] = 200;
        $response['message'] = 'User successfully signed out';
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }
}
