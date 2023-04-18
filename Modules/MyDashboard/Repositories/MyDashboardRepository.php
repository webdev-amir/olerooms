<?php

namespace Modules\MyDashboard\Repositories;

use Carbon\Carbon;
use Session;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;
use Modules\Notifications\Repositories\NotificationRepositoryInterface as NotificationRepositoryInterface;
use Modules\Notifications\Entities\Notifications;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\Booking\Entities\Booking;
use Modules\PropertyType\Entities\PropertyType;
use Modules\ScheduleVisit\Entities\ScheduleVisit;

class MyDashboardRepository implements MyDashboardRepositoryInterface
{

    public $Tasks;

    function __construct(
        EmailNotificationsRepository $EmailNotificationsRepository,
        NotificationRepositoryInterface $NotificationRepositoryInterface,
        CommonRepo $CommonRepo,
        ScheduleVisit $ScheduleVisit,
        Booking $Booking
    ) {
        $this->EmailNotificationsRepo = $EmailNotificationsRepository;
        $this->NotificationsRepository = $NotificationRepositoryInterface;
        $this->CommonRepo = $CommonRepo;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->Booking = $Booking;
    }

    public function getMyPaymentsHistory($request)
    {
        $payments = $this->Payments->with(['order', 'payplan', 'order.plan', 'order.plan.userPlan'])->where('id', '!=', 0)->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->whereHas('order', function (Builder $q) {
            $q->where('user_id', auth()->user()->id);
            $q->where('is_paid', 1);
        });
        return $payments->paginate(10);
    }

    public function getRecordBySlug($module, $slug)
    {
        $record = $this->$module->findBySlug($slug);
        return $record;
    }

    public function getAllNotifications($request)
    {
        $notifications = Notifications::where('user_id', auth()->id());
        Notifications::where('user_id', auth()->id())->update(array('read_at' => now()));
        if ($request->get('readStatus')) {
            $notifications = $notifications->where('read_at', $request('readStatus'));
        }
        return $notifications->orderBy('created_at', 'DESC')->paginate(\config::get('custom.default_pagination'));
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name', 'gender', 'city', 'phone', 'email', 'address', 'marital_status','occupation');
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
                auth()->user()->sendEmailVerificationNotification();
                return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('customer.dashboard.myprofile')];
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('customer.dashboard.myprofile')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function sendOtpResponse()
    {
        $response = $this->CommonRepo->CreateAndSendOtp(auth()->user()->id);
        if ($response) {
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.please_enter_otp_to_update_mobile_no'), 'otp_mobile_update' => true];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function updateUserProfileDetailsWithOTP($request)
    {
        $response = $this->CommonRepo->VerifyOtp(auth()->user()->id, auth()->user()->mobileVerifired->otp_verification_code, $request->mobile_otp);
        if ($response) {
            $filleable = $request->only('name', 'gender', 'phone', 'city', 'address', 'email', 'marital_status');
            if ($request->dob) {
                $filleable['dob'] = date("Y-m-d", strtotime($request->dob));
            }
            if ($request->image) {
                $filleable['image'] = $request->image;
            }

            if (auth()->user()->update($filleable)) {
                return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'otp_Box_hide' => true];
            } else {
                return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
            }
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.otp_does_not_matched')];
        }
    }

    public function deleteAccount($request)
    {
        if (auth()->user()) {
            $this->EmailNotificationsRepo->sendAccountDeletedInfoEmail(auth()->user()->email);
            auth()->user()->delete();
            session()->flush();
            auth()->logout();

            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account deleted successfully', 'url' => route('auth.login'), 'modelClose' => 'deleteAcoount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
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

    public function getAllPropertyTypes()
    {
        return  PropertyType::where('status', 1)->get();
    }

    public function getAllMyBookingsRecord($request)
    {
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $bookings = $this->Booking->where('user_id', auth()->user()->id)->whereNotIn('status', ['in-progress', 'request']);
        if ($request->get('property_type')) {
            $property_type = $request->get('property_type');
            $bookings = $bookings->whereHas('Property.propertyType', function ($query) use ($property_type) {
                $query->where("property_types.slug", $property_type);
            });
        }
        $to = date("Y-m-d", strtotime($request->get('to') . "+1 day"));
        $from = date("Y-m-d", strtotime($request->get('from')));
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

    public function MyBookingsDetails($slug)
    {
        $data = $this->Booking->where('slug', $slug)->first();
        return $data;
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
