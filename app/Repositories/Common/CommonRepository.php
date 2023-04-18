<?php

namespace App\Repositories\Common;

use DB, Mail, Session;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\Configuration\Entities\Configuration;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\StaticPages\Entities\StaticPages;
use Modules\Booking\Entities\Booking;
use Modules\PropertyType\Entities\PropertyType;
use App\Models\Country;
use Modules\City\Entities\City;
use Carbon\Carbon;
use Exception;
use Modules\Users\Entities\MobileVerification;
use Modules\Payment\Entities\Payment;
use Softon\Sms\Facades\Sms;

class CommonRepository implements CommonRepositoryInterface
{

    function __construct(User $User, StaticPages $StaticPages)
    {
        $this->User = $User;
        $this->StaticPages = $StaticPages;
    }

    public function getUsersPluck()
    {
        return $this->User->select('*', DB::raw('CONCAT(users.first_name, " ",users.last_name, "") AS new_name'))->whereHas('roles', function (Builder $q) {
            $q->where('slug', 'user');
        })->pluck('new_name', 'id')->toArray();
    }

    public function getAllPagesListPluck()
    {
        return $this->StaticPages->pluck('name_en', 'id')->toArray();
    }

    public function getUserCountsByRoles($request, $role, $verification_status = '')
    {
        $to  = $request->get('to');
        $from  = $request->get('from');
        $users = $this->User->whereHas('roles', function (Builder $q) use ($role) {
            if ($role) {
                $q->where('slug', $role);
            }
        });

        if ($verification_status != '') {
            if ($verification_status == 'pending') {
                $users = $users->whereHas('userCompleteProfilePending', function (Builder $q) {
                    // 
                });
            }

            if ($verification_status == 'approved') {
                $users = $users->whereHas('userCompleteProfileVerifiredIfApproved', function (Builder $q) {
                    // 
                });
            }
        }
        if (!empty($from)) {
            $from  = date("Y-m-d", strtotime($from));
            $users = $users->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $to  = date("Y-m-d", strtotime($to . "+1 day"));
            $users = $users->where('created_at', '<=', $to);
        }
        return $users->count();
    }

    public function saveProfilePictureMedia($request)
    {
        $filename = uploadWithResize($request->file('files'), 'users/');
        if ($request->get('user_id')) {
            $user = $this->User->find($request->get('user_id'));
            $user->image = $filename;
            $user->save();
        }
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['s3FullPath'] = \Storage::disk('s3')->url('users/' . $filename);
        $response['filename'] =  $filename;
        return $response;
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name', 'gender', 'email', 'marital_status');
        if ($request->dob) {
            $filleable['dob'] = date("Y-m-d", strtotime($request->dob));
        }
        if ($request->image) {
            $filleable['image'] = $request->image;
        }

        if (auth()->user()->update($filleable)) {
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }


    public function sendMobileOtp($userId)
    {
        MobileVerification::where('user_id', $userId)->delete();
        $otpCode = 1234;
        if (\Config::get('sms.GUPSHUP_START') == 'YES') {
            // $otpCode = rand(0000, 9999);
            $otpCode = $this->createOTP(4);
        }

        $result =  MobileVerification::create([
            'user_id' =>  $userId,
            'otp_verification_code' =>  $otpCode,
        ]);

        if (\Config::get('sms.GUPSHUP_START') == 'YES') {
            $res = Sms::send($result->user->phone, 'OTP for Login on OLE ROOMS is ' . $otpCode . ' and valid Do not share  this OTP to anyone for security reasons. Team ' . \Config::get('app.name'));
        }

        if (env('TWILLIO_START') == 'YES') {
            \Twilio::message($result->user->NotificationNumber, 'Hi , This is OTP verification code to verify mobile ' . $otpCode . ', Thanks By ' . \Config::get('app.name'));
        }
        !($result) ? false : true;
    }

    public function updateUserMobile($request)
    {
        $filleable = $request->only('phone');
        if (auth()->user()->update($filleable)) {
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function VerifyOtp($user_id, $user_OTP, $request_OTP)
    {
        if ($user_OTP == $request_OTP) {
            MobileVerification::where(['user_id' => $user_id, 'otp_verification_code' => $request_OTP])->delete();
            return true;
        }
        return false;
    }

    public function CreateAndSendOtp($user_id, $session = false)
    {
        MobileVerification::where(['user_id' => $user_id])->delete();
        if ($session) {
            session()->put(['userId' => $user_id]);
        }
        $otpCode = 1234;
        if (\Config::get('sms.GUPSHUP_START') == 'YES') {
            // $otpCode = rand(0000, 9999);
            $otpCode = $this->createOTP(4);
        }

        $result =  MobileVerification::create([
            'user_id' =>  $user_id,
            'otp_verification_code' =>  $otpCode,
        ]);

        if (\Config::get('sms.GUPSHUP_START') == 'YES') {
            $res = Sms::send($result->user->phone, 'OTP for Login on OLE ROOMS is ' . $otpCode . ' and valid Do not share  this OTP to anyone for security reasons. Team ' . \Config::get('app.name'));
        }

        if (env('TWILLIO_START') == 'YES') {
            \Twilio::message($result->user->NotificationNumber, 'Hi , This is OTP verification code to verify mobile ' . $otpCode . ', Thanks By ' . \Config::get('app.name'));
        }
        return empty($result) ? false : true;
    }

    public function uploadUserDcuments($request)
    {
        $filename = uploadOnS3Bucket($request->file('files'), 'users/');
        $response['status_code'] = 252;
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['type'] = 'success';
        $response['message'] = 'Document uploaded successfully';
        return $response;
    }


    public function uploadSelfyAndLogoMedia($request)
    {
        $filename = uploadWithResize($request->file('files'), 'users/');
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['s3FullPath'] = \Storage::disk('s3')->url('users/' . $filename);

        return $response;
    }

    public function uploadBankImages($request)
    {
        $filename = uploadWithResize($request->file('files'), 'users/bankdetails/' . auth()->id() . '/');
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['s3FullPath'] = \Storage::disk('s3')->url('users/bankdetails/' . auth()->id() . '/' . $filename);

        return $response;
    }




    public function updateUserPassword($request)
    {
        $user = $this->User->find($request->id);

        if ($user) {
            if (Hash::check($request->get('old_password'), $user->password)) {

                if ($request->get('old_password') != $request->get('password')) {
                    $this->resetPassword($user, $request->password);
                    $response['message'] = trans('flash.success.password_has_been_changed');
                    $response['type'] = 'success';
                    $response['status_code'] = 200;
                    $response['reset'] = 'true';
                } else {
                    $response['message'] = trans('flash.error.new_password_can_not_match');
                    $response['type'] = 'error';
                    $response['status_code'] = 400;
                }
            } else {
                $response['message'] = trans('flash.error.please_enter_correct_old_password');
                $response['type'] = 'error';
                $response['status_code'] = 400;
            }
        } else {
            $response['message'] = trans('flash.error.oops_something_went_wrong');
            $response['type'] = 'error';
            $response['status_code'] = 200;
        }
        return $response;
    }

    public function downloadS3File($request)
    {
        try {
            $assetPath = \Storage::disk('s3')->url($request->get('fp'));
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=" . basename($assetPath));
            header("Content-Type: " . '[image/jpeg,application/pdf,application/vnd.ms-excel,application/zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/svg+xml,image/png,video/mp4,image/gif,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,text/csv]');
            return readfile($assetPath);
        } catch (Exception $ex) {
            return false;
        }
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => trim(Hash::make($password)),
            'remember_token' => Str::random(60),
        ])->save();
    }

    public function getConfigValue($slug)
    {
        $config = Configuration::findBySlug($slug);
        if ($config)
            return $config->config_value;
        else
            return '';
    }

    public function getStaticPageRecordBySlug($slug)
    {
        return StaticPages::findBySlug($slug);
    }

    public function getCityPluck()
    {
        return City::where('status', '1')->pluck('name', 'id')->toArray();
    }
    public function getCountryPluck()
    {
        return Country::pluck('countryName', 'id')->toArray();
    }

    public function getCountryCodesPluck()
    {
        return  Country::select('*', DB::raw('CONCAT(countries.code, " ( ", countries.countryName, " ) ") AS full_name'))->orderBy('countries.sort_order', 'ASC')->pluck('countries.full_name', 'code')->toArray();
    }

    public function getCountryListPluck()
    {
        return Country::select('*', DB::raw('CONCAT(countries.code, " ( ", countries.countryName, " ) ") AS full_name'))->orderBy('countries.sort_order', 'ASC')->pluck('countries.full_name', 'id')->toArray();
    }

    public function getPropertyTypesPluck()
    {
        return PropertyType::where('status', 1)->pluck('name', 'id')->toArray();
    }

    public function getPropertyTypesOptions()
    {
        return PropertyType::where('status', 1)->orderBy('sortby', 'asc')->limit(6)->get();
    }

    public function getSocialLinkData()
    {
        $records = Configuration::whereIn('slug', ['facebook', 'instagram', 'twitter', 'pinterest', 'adminemail', 'admincontact', 'linkedin'])->get();
        $socialLinkData = NULL;
        if (!empty($records)) {
            foreach ($records as $item) {
                $socialLinkData[$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
            }
        }
        return $socialLinkData;
    }

    public function getBookingsCount($request)
    {
        $bookings = Booking::whereNotIn('status', Booking::notAcceptedStatus);
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $bookings = $bookings->where('created_at', '>=', Carbon::parse($from)->format('Y-m-d 00:00:00'));
        }
        if (!empty($to)) {
            $bookings = $bookings->where('created_at', '<=', Carbon::parse($to)->format('Y-m-d 11:59:59'));
        }

        return $bookings->count();
    }


    public function getCancelledBookingsCount($request)
    {
        $bookings = Booking::where('cancel_request_date', '!=', NULL);
        return $bookings->count();
    }

    public function getPaymentsCount($request)
    {
        $bookings = Booking::whereNotIn('status', ['pending']);
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $bookings = $bookings->where('created_at', '>=', Carbon::parse($from)->format('Y-m-d 00:00:00'));
        }
        if (!empty($to)) {
            $bookings = $bookings->where('created_at', '<=', Carbon::parse($to)->format('Y-m-d 11:59:59'));
        }

        return $bookings->sum('commission');
    }

    public function createOTP($digits = 4)
    {

        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }
}
