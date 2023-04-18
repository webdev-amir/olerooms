<?php

namespace Modules\Api\Repositories\Customer\Profile;

use config;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Modules\Users\Entities\MobileVerification;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class ProfileRepository implements ProfileRepositoryInterface
{

    public $User;
    public $CommonRepo;
    
    function __construct(User $User, CommonRepo $CommonRepo)
    {
        $this->User = $User;
        $this->CommonRepo = $CommonRepo;
    }

    public function getCustomerProfileDetails($request)
    {
        try {
            $user = auth()->user();
            $user['user_image_path'] = $user->ThumbPicturePath;
            $response['status_code'] = 200;
            $response['message'] = 'User Profile details';
            $response['data'] = $user;
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getProfileFormData($request)
    {
        try {
            $response['status_code'] = 200;
            $response['message'] = 'Profile Masters Data';
            $response['data']['maritalStatusList'] = Config::get('custom.marital-status-list');
            $response['data']['genderList'] = Config::get('custom.gender-list');
            $response['data']['cityList'] = $this->CommonRepo->getCityPluck();
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function updateUserProfileDetails($request)
    {
        try {
            $user = auth()->user();
            $userOldEmail = $user->email;
            $filleable = $request->only('name', 'gender', 'city', 'address', 'email', 'marital_status', 'image');
            if ($request->dob) {
                $filleable['dob'] = date("Y-m-d", strtotime($request->dob));
            }

            if ($request->email != $user->email) {
                $filleable['email_verified_at'] = NULL;
            }
            if ($user->update($filleable)) {
                $response['status_code'] = 200;
                $response['message'] = 'Profile updated sucessfully';
                if ($request->email != $userOldEmail) {
                    $response['email_change'] = 'Please verify your account by clicking on the link shared on the registered email ID.';
                }
                $response['data'] = $user;
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Profile not updated';
                $response['data'] = [];
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'something went wrong';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function updateCustomerPhoneNumber($request)
    {
        try {
            $user = auth()->user();
            if ($request->mobile_otp) {
                $response =  $this->updateUserProfileDetailsWithOTP($request);
            } elseif ($request->phone != $user->phone) {
                $response = $this->CreateAndSendOtp($request);
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Please enter different mobile number to update';
                $response['data'] = [];
            }
        } catch (\Exception $e) {
            $response['status_code'] = 401;
            $response['message'] = $e->getMessage();
            $response['data'] = [];
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function updateUserProfileDetailsWithOTP($request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_otp' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $user = auth()->user();
        $userOtp =  MobileVerification::where('user_id', $user->id)->first();
        if ($userOtp && $userOtp->otp_verification_code) {
            $requestOtp = $request->mobile_otp;
            if ($userOtp->otp_verification_code == $requestOtp) {
                MobileVerification::where(['user_id' => $user->id, 'otp_verification_code' => $requestOtp])->delete();
                $filleable = $request->only('phone');
                $filleable['mobile_verified_at'] = now();
                if ($user->update($filleable)) {
                    $response['status_code'] = 200;
                    $response['message'] = 'Profile updated sucessfully';
                    $response['data'] = $user;
                    return $response;
                } else {
                    $response['status_code'] = 200;
                    $response['message'] = 'Profile not updated';
                    $response['data'] = [];
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Otp does not match with our records';
                $response['data'] = [];
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'Otp does not match with our records';
            $response['data'] = [];
        }
        return $response;
    }

    public function CreateAndSendOtp($user)
    {
        $user = auth()->user();
        MobileVerification::where('user_id', $user->id)->delete();
        $result =  MobileVerification::create([
            'user_id' => $user->id,
            'otp_verification_code' => 1234,
        ]);
        $response['status_code'] = 200;
        $response['message'] = 'Otp Sent your mobile number';
        return $response;
    }

    public function accountDelete($request)
    {
        $userDelete = auth()->user();
        if ($userDelete) {
            auth()->logout();
            $response['status_code'] = 200;
            $response['message'] = 'User Delete Sucessfully';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }
    public function changePassword($request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'confirmed', 'min:8'],
            'old_password' =>  ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $authUser = auth()->user();
            $user =  $this->User->where('id', $authUser->id)->first();
            $passwordCheck = Hash::check($request->old_password, $user->password);
            if ($passwordCheck) {
                $updatePassword = Hash::make($request->new_password);
                $user->update(array("password" => $updatePassword));
                $response['status_code'] = 200;
                $response['message'] = 'password updated sucessfully';
                $response['data'] = $user;
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'current password is invalid';
                $response['data'] = $user;
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }
    public function getSignedURL($request)
    {
        $location = 'users';
        $s = strtoupper(md5(uniqid(rand(), true)));
        $guidText =
            substr($s, 0, 8) . '-' .
            substr($s, 8, 4) . '-' .
            substr($s, 12, 4) . '-' .
            substr($s, 16, 4) . '-' .
            substr($s, 20);
        $prevFileName = $location . '/' . $guidText . '.jpg';
        $filename = $guidText . '.jpg';
        $prevFileUrl = \Storage::disk('s3')->url($prevFileName);
        $s3 = \Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+20 minutes";
        $bucketName = env("AWS_BUCKET");
        $command = $client->getCommand('putObject', [
            'Bucket' => $bucketName,
            'Key'    => $prevFileName,
            'ACL' => 'public-read',
            'ContentType' => 'image/jpeg'
        ]);

        $return = $client->createPresignedRequest($command, $expiry);
        if (isset($return)) {
            $signeUrl = (string)$return->getUri();
            $response = [
                'status_code' =>  200,
                'message' =>  'URL created successfully',
            ];
            $response['data']['url'] = $signeUrl;
            $response['data']['filename'] = $filename;
            $response['data']['preview_file_url'] = $prevFileUrl;
            return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
        } else {
            return response()->json([
                'status_code' => 400,
                'message' => 'URL not created',
                'data' => $dataArray
            ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode(400);
        }
    }
}
