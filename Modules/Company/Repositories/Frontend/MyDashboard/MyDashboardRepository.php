<?php

namespace Modules\Company\Repositories\Frontend\MyDashboard;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Entities\Payment;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;
use Modules\Property\Entities\Property;
use Modules\Booking\Entities\Booking;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Booking\Repositories\Frontend\BookingRepository as FrontendBookingRepository;
use Modules\Notifications\Entities\Notifications;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\Wallet\Entities\RedeemCreditRequest;
use Modules\Wallet\Entities\Wallet;


class MyDashboardRepository implements MyDashboardRepositoryInterface
{
    protected $Booking;
    public $NotificationsRepository;
    public $EmailNotificationsRepo;
    public $ScheduleVisit;
    public $RedeemCreditRequest;
    public $FrontendBookingRepository;
    public $Wallet;

    function __construct(
        EmailNotificationsRepo $EmailNotificationsRepo,
        Booking $Booking,
        Wallet $Wallet,
        ScheduleVisit $ScheduleVisit,
        RedeemCreditRequest $RedeemCreditRequest,
        FrontendBookingRepository $FrontendBookingRepository
    ) {
        $this->Wallet = $Wallet;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->Booking = $Booking;
        $this->FrontendBookingRepository = $FrontendBookingRepository;
        $this->RedeemCreditRequest = $RedeemCreditRequest;
        $this->ScheduleVisit = $ScheduleVisit;
    }

    public function getDashboardRecord($request)
    {
        $response['totalEarnings'] = numberformatWithCurrency(auth()->user()->UserTotalEarnings, 2);
        $response['totalCodeBookings'] = $this->Booking->where([['code_type', 'company'], ['agent_corp_code', auth()->user()->company_code]])->whereNotIn('status', ['in-progress', 'request'])->count();
        $response['totalBookings'] = $this->Booking->where('user_id', auth()->user()->id)->whereNotIn('status', ['in-progress', 'request'])->count();
        $response['currentBookings'] = $this->Booking->where([['user_id', auth()->user()->id], ['status', 'confirmed']])->count();
        $response['currentWalletAmount'] = auth()->user()->UserWalletAmount;

        return $response;
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name', 'phone', 'city', 'email', 'state_id', 'map_location', 'lat', 'long');
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
                return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('company.dashboard.myprofile')];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function updateBankDetails($request)
    {
        $filleable = $request->only('account_number', 'holder_name', 'bank_name', 'ifsc_code', 'pan_card_number', 'pan_card_image', 'cancelled_cheque_image', 'gstin_number');
        $user = auth()->user();
        if ($user->userBankDetail) {
            if ($user->userBankDetail()->update($filleable)) {
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details updated successfully'];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details not updated'];
        } else {
            if ($user->userBankDetail()->create($filleable)) {
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details updated successfully'];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Bank details not updated'];
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

    public function getAllMyBookingsRecord($request)
    {
        $type  = ($request->get('type')) ? $request->get('type') : 'all';
        $bookings = $this->Booking->where('user_id', auth()->user()->id);
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

        return $bookings->where('status', '!=', 'in-progress')->latest()->paginate(\config::get('custom.default_pagination'));
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


    public function getAllMyCodeBookingsRecord($request)
    {
        $type  = ($request->get('type')) ? $request->get('type') : 'all';
        $bookings = $this->Booking->where('code_type', 'company')->where('agent_corp_code', auth()->user()->company_code)->whereNotIn('status', ['in-progress', 'request']);
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

        return $bookings->where('status', '!=', 'in-progress')->latest()->paginate(\config::get('custom.default_pagination'));
    }



    public function getBookingRequests()
    {
        $bookings = $this->Booking->where('user_id', auth()->user()->id)->where('check_in_date', '>', now())->whereIn('status', ['confirmed', 'pending']);

        return $bookings->latest()->paginate(4);
    }

    public function getMyPaymentsEarningHistory($request)
    {
        $payments = $this->getMyPointsEarningsRecords($request);
        $response['totalEarnings'] = numberformatWithCurrency(auth()->user()->UserTotalEarnings, 2);
        $response['totalBookings'] = $this->Booking->where([['code_type', 'company'], ['agent_corp_code', auth()->user()->company_code]])->whereNotIn('status', ['in-progress',  'request'])->count();
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

    public function MyBookingsDetails($slug)
    {
        return $this->Booking->where('slug', $slug)->with(['Property'])->first();
    }




    public function getAllPropertiesForMyEarningsFilter()
    {
        return Property::where('user_id', auth()->user()->id)->whereHas('bookings', function ($query) {
            //
        })->where('status', 'publish')->pluck('property_name', 'id')->toArray();
    }

    protected function guard()
    {
        return Auth::guard();
    }


    public function getAllMyVisitRecord($request)
    {
        $myvisits = $this->ScheduleVisit->where('user_id', auth()->user()->id)->whereNotIn('status', ScheduleVisit::notAcceptedStatus)->whereHas('scheduleVisitProperty', function ($query) {
        });

        if ($request->get('type')) {
            if ($request->get('type') == 'past_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '<', now()->format("Y-m-d"));
                });
            } else if ($request->get('type') == 'upcoming_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '>', now()->format("Y-m-d"));
                });
            } else if ($request->get('type') == 'active') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '=', now()->format("Y-m-d"));
                });
            } else if ($request->get('type') == 'cancelled') {
                $myvisits = $myvisits->where("status", '=', 'cancelled');
            }
        }

        return $myvisits->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function myvisitDetailsRecord($slug)
    {
        $myvisitData = $this->ScheduleVisit->with(["scheduleVisitProperty", 'scheduleVisitStartingProperty'])->where('slug', $slug)
            ->where('status', '!=', 'request')
            ->first();
        return $myvisitData;
    }


    public function cancellBookingRequest($request)
    {
        $response = [];
        try {
            $model = $request->actionName;
            $booking = $this->Booking->where('slug', $request->slug)->first();
            if ($booking) {
                if ($booking->status == 'cancelled') {
                    $message = "Booking already cancelled!";
                    $type = 'error';
                } elseif ($booking->CancellationBeforeDate == false) {
                    $message = "Your booking already checked-In , Can't be cancelled now";
                    $type = 'error';
                } elseif ($booking->cancel_request_date) {
                    $message = "Already requested for cancellation on " . Carbon::parse($booking->cancel_request_date)->format('d M Y');
                    $type = 'error';
                } else {
                    $booking->cancel_request_date = now();
                    $booking->cancellation_reason = $request->description;
                    $booking->booking_cancelled_reject_date = NULL;
                    $booking->save();
                    $message = "Cancel request submitted successfully!";
                    $type = 'success';
                    $this->EmailNotificationsRepo->sendBookingCancellationRequestEmail(auth()->user(), $booking);
                    $this->EmailNotificationsRepo->sendBookingCancellationRequestEmailToVendor(auth()->user(), $booking);
                }
            } else {
                $message = "Booking not found!";
                $type = 'error';
            }
            $response['status_code'] = 200;
            $response['message'] = $message;
            $response['type'] = $type;
            $response['url'] = route('customer.dashboard.mybooking');
            $response['modelClose'] = 'cancelledBookingMod';
            return $response;
        } catch (\Exception $e) {
            $response['status_code'] = 400;
            $response['message'] = $e->getMessage();
            $response['type'] = 'error';
            Session::flash($response['type'], $response['message']);
            return $response;
        }
    }

    public function cancellVisitRequest($request)
    {
        $response = [];
        try {
            $model = $request->actionName;
            $visit = $this->ScheduleVisit->where('slug', $request->slug)->first();
            if ($visit) {
                if ($visit->status == 'cancelled') {
                    $message = "Your schedule visit already cancelled!";
                    $type = 'error';
                } elseif ($visit->CancellationBeforeDate == false) {
                    $message = "Your schedule visit already checked-In , Can't be cancelled now";
                    $type = 'error';
                } elseif ($visit->cancel_request_date) {
                    $message = "Already requested for cancellation on " . Carbon::parse($visit->cancel_request_date)->format('d M Y');
                    $type = 'error';
                } else {
                    $visit->cancel_request_date = now();
                    $visit->cancellation_reason = $request->description;
                    $visit->schedule_visit_cancelled_reject_date = NULL;
                    $visit->save();
                    $message = "Cancel request submitted successfully!";
                    $type = 'success';
                    $this->EmailNotificationsRepo->sendVisitCancellationRequestEmail(auth()->user(), $visit);
                    foreach ($visit->scheduleVisitProperty as $visitProperty) {
                        $this->EmailNotificationsRepo->sendVisitCancellationRequestEmailToVendor(auth()->user(), $visit, $visitProperty);
                    }
                }
            } else {
                $message = "Booking not found!";
                $type = 'warning';
            }
            $response['url'] = route('customer.dashboard.myvisit');
            $response['status_code'] = 200;
            $response['message'] = $message;
            $response['type'] = $type;
            $response['url'] = route('customer.dashboard.myvisit');
            $response['modelClose'] = 'scheduleCancelledMod';
            return $response;
        } catch (\Exception $e) {
            $response['status_code'] = 400;
            $response['message'] = $e->getMessage();
            $response['type'] = 'error';

            Session::flash($response['type'], $response['message']);
            return $response;
        }
    }
}
