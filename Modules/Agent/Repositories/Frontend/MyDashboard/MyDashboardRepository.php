<?php

namespace Modules\Agent\Repositories\Frontend\MyDashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Modules\Booking\Entities\Booking;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Wallet\Entities\RedeemCreditRequest;
use Modules\Wallet\Entities\Wallet;
use Modules\Booking\Repositories\Frontend\BookingRepository as FrontendBookingRepository;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepo;
use Modules\Property\Entities\Property;
use Modules\Notifications\Entities\Notifications;

class MyDashboardRepository implements MyDashboardRepositoryInterface
{
    public $NotificationsRepository;
    public $EmailNotificationsRepo;
    public $Booking;
    public $Wallet;
    public $RedeemCreditRequest;
    public $FrontendBookingRepository;

    function __construct(
        EmailNotificationsRepo $EmailNotificationsRepo,
        Booking $Booking,
        Wallet $Wallet,
        RedeemCreditRequest $RedeemCreditRequest,
        FrontendBookingRepository $FrontendBookingRepository
    ) {
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->Booking = $Booking;
        $this->Wallet = $Wallet;
        $this->RedeemCreditRequest = $RedeemCreditRequest;
        $this->FrontendBookingRepository = $FrontendBookingRepository;
    }

    public function getDashboardRecord($request)
    {
        $response['totalEarnings'] = numberformatWithCurrency(auth()->user()->UserTotalEarnings, 2);
        $response['totalBookings'] = $this->Booking->where([['code_type', 'agent'], ['agent_corp_code', auth()->user()->agent_code]])->whereNotIn('status', ['in-progress', 'request'])->count();
        $response['currentWalletAmount'] = auth()->user()->UserWalletAmount;
        return $response;
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name', 'gender', 'phone', 'email', 'address', 'marital_status');
        if ($request->dob) {
            $filleable['dob'] = date("Y-m-d", strtotime($request->dob));
        }
        if ($request->image) {
            $filleable['image'] = $request->image;
        }

        if ($request->email != auth()->user()->email) {
            $filleable['email_verified_at'] = null;
        }

        if (auth()->user()->update($filleable)) {
            if (auth()->user()->email_verified_at == null) {
                $this->EmailNotificationsRepo->sendVerifyEmail($request, auth()->user());
                return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('agent.dashboard.myprofile')];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('agent.dashboard.myprofile')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function updateBankDetails($request)
    {
        $filleable = $request->only('account_number', 'holder_name', 'bank_name', 'ifsc_code', 'upi_qr_code_image', 'upi_id');
        $user = auth()->user();
        if ($user->userBankDetail) {
            if ($user->userBankDetail()->update($filleable)) {
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details updated successfully.'];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details not updated.'];
        } else {
            if ($user->userBankDetail()->create($filleable)) {
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details updated successfully.'];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details not updated.'];
        }
    }

    public function deactivateAccount($request)
    {
        auth()->user()->deactivate_at = now();
        if (auth()->user()->save()) {
            session()->flush();
            Auth::logout();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account Deactivated Successfully', 'url' => route('agent.login'), 'modelClose' => 'deactivateAccount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function deleteAccount($request)
    {
        $user = auth()->user();
        if (auth()->user()->delete()) {
            $this->EmailNotificationsRepo->sendDeleteAccountMailandNotification($user);
            session()->flush();
            Auth::logout();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account deleted successfully', 'url' => route('agent.login'), 'modelClose' => 'deleteAcoount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function getAllPropertyTypes()
    {
        return PropertyType::where('status', 1)->get();
    }

    public function getAllNotifications($request)
    {
        $notifications = Notifications::where('user_id', auth()->id());
        $notifications->update(array('read_at' => now()));
        if ($request->get('readStatus')) {
            $notifications = $notifications->where('read_at', $request('readStatus'));
        }
        return $notifications->orderBy('created_at', 'DESC')->paginate(\config::get('custom.default_pagination'));
    }

    public function getAllMyBookingsRecord($request)
    {
        $type  = ($request->get('type')) ? $request->get('type') : 'all';
        $bookings = $this->Booking->where([['code_type', 'agent'], ['agent_corp_code', auth()->user()->agent_code]])->whereNotIn('status', ['in-progress', 'request']);
        if ($request->get('property_type')) {
            $property_type =   $request->get('property_type');
            $bookings = $bookings->whereHas('property.propertyType', function ($query) use ($property_type) {
                $query->where("property_types.slug", $property_type);
            });
        }
        $to  = date("Y-m-d", strtotime($request->get('to') . "+1 day"));
        $from  = date("Y-m-d", strtotime($request->get('from')));
        if (!empty($from)) {
            $bookings = $bookings->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $bookings = $bookings->where('created_at', '<=', $to);
        }

        if ($type == 'active') {
            $bookings = $bookings->where('check_in_date', now()->format("Y-m-d"))->whereNotIn('status', ['cancelled', 'rejected']);
        } else if ($type == 'upcoming') {
            $bookings = $bookings->where('check_in_date', '>', now()->format("Y-m-d"))->whereNotIn('status', ['cancelled', 'rejected']);
        } else if ($type != 'all') {
            $bookings = $bookings->where('status', $type);
        }

        return $bookings->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function getMyPaymentsEarningHistory($request)
    {
        $payments = $this->getMyPointsEarningsRecords($request);
        $response['totalEarnings'] = numberformatWithCurrency(auth()->user()->UserTotalEarnings, 2);
        $response['totalBookings'] = $this->Booking->where([['code_type', 'agent'], ['agent_corp_code', auth()->user()->agent_code]])->whereNotIn('status', ['in-progress',  'request'])->count();
        $response['records'] = $payments->paginate(\config::get('custom.default_pagination'));
        return $response;
    }

    public function getMyPointsEarningsRecords($request)
    {
        $payments = Wallet::orderBy('created_at', 'desc')->where('user_id', auth()->user()->id)->with(['booking', 'booking.property'])->whereHas('booking', function (Builder $q) use ($request) {
            if ($request->get('property_type')) {
                $q->where('bookings.property_id', $request->get('property_type'));
            }
        });
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $from  = date("Y-m-d", strtotime($from));
            $payments = $payments->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $to  = date("Y-m-d", strtotime($to . "+1 day"));
            $payments = $payments->where('created_at', '<=', $to);
        }
        return $payments;
    }

    public function MyBookingsDetails($request, $slug)
    {
        $data = $this->Booking->where('slug', $slug)->where([['code_type', 'agent'], ['agent_corp_code', auth()->user()->agent_code]])->with(['Property'])->first();
        return $data;
    }


    public function getBookingRequests($request)
    {
        $bookingRequests = $this->Booking->where([['code_type', 'agent'], ['agent_corp_code', auth()->user()->agent_code]])->whereHas('property', function ($query) {
        })->whereNotIn('status', ['in-progress', 'pending', 'request']);
        return $bookingRequests->latest()->limit(4)->get();
    }

    public function getAllPropertiesForMyEarningsFilter()
    {
        return Property::whereHas('bookings', function ($query) {
            $query->where('bookings.code_type', 'agent');
            $query->where("bookings.agent_corp_code", auth()->user()->agent_code);
        })->where('status', 'publish')->pluck('property_name', 'id')->toArray();
    }

    public function sendRedeemCreditRequest($request)
    {
        $user = auth()->user();
        $request['user_id'] = $user->id;
        $request['amount'] = auth()->user()->UserWalletAmount;
        if (auth()->user()->UserWalletAmount < 1) {
            $data = [
                'message' => 'Your wallet amount must be greater or equal ' . numberformatWithCurrency(1),
                'status_code' => 500,
                'type' => 'error',
                'walletAmount' => numberformatWithCurrency(auth()->user()->UserWalletAmount),
            ];
            return $data;
        }
        if (auth()->user()->UserWalletAmount < $request->get('amount')) {
            $data = [
                'message' => 'Your wallet amount must be greater or equal ' . numberformatWithCurrency($request->get('amount')),
                'status_code' => 500,
                'type' => 'error',
                'walletAmount' => numberformatWithCurrency(auth()->user()->UserWalletAmount),
            ];
            return $data;
        }
        $request['status'] = 'pending';
        $redeem = $this->RedeemCreditRequest::create($request->all());
        $this->Wallet->create([
            'user_id' => auth()->user()->id,
            'type' => 'debit',
            'amount' => $redeem->amount,
            'description' => 'Redeem from wallet',
        ]);
        //$this->sendCreditRedeemRequestEmailForAdmin($redeem);
        $response['type'] = 'success';
        $response['status_code'] = 200;
        $response['reset'] = 'true';
        $response['message'] = trans('Redeem request submitted successfully');
        $response['walletAmount'] = numberformatWithCurrency($redeem->user->UserWalletAmount);
        return $response;
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
