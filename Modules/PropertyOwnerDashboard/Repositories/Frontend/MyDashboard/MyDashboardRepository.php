<?php

namespace Modules\PropertyOwnerDashboard\Repositories\Frontend\MyDashboard;

use DB;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Entities\Payment;
use Illuminate\Database\Eloquent\Builder;
use App\Providers\RouteServiceProvider;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;
use Modules\Notifications\Repositories\NotificationRepositoryInterface as NotificationRepositoryInterface;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;
use Modules\Notifications\Entities\Notifications;
use Modules\Property\Entities\Property;
use Modules\Property\Entities\PropertyOffers;
use Modules\Coupon\Entities\Coupon;
use Modules\Booking\Entities\Booking;
use Modules\PropertyType\Entities\PropertyType;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\Review\Entities\Review;
use Modules\Booking\Repositories\Frontend\BookingRepository as FrontendBookingRepository;

class MyDashboardRepository implements MyDashboardRepositoryInterface
{
    function __construct(
        EmailNotificationsRepository $EmailNotificationsRepository,
        NotificationRepositoryInterface $NotificationRepositoryInterface,
        EmailNotificationsRepo $EmailNotificationsRepo,
        Property $Property,
        Booking $Booking,
        PropertyOffers $PropertyOffers,
        ScheduleVisit $ScheduleVisit,
        Payment $Payment,
        FrontendBookingRepository $FrontendBookingRepository
    ) {
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->NotificationsRepository = $NotificationRepositoryInterface;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->Property = $Property;
        $this->Booking = $Booking;
        $this->PropertyOffers = $PropertyOffers;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->Payment = $Payment;
        $this->FrontendBookingRepository = $FrontendBookingRepository;
    }

    public function getDashboardRecord($request)
    {

        $response['propertyCount'] = $this->Property->where('user_id', auth()->user()->id)->count();
        $fullTypeBookings = $this->Booking->getFullPaymentAmountSumVendorAttribute() - $this->Booking->getCommissionPaymentAmountSumVendorAttribute();
        $partialTypeBookings = $this->Booking->getPartialPaymentAmountSumVendorAttribute();
        $response['totalEarnings'] = numberformatWithCurrency($fullTypeBookings  + $partialTypeBookings);
        $response['totalBookings'] = $this->Booking->getAllBookingsSumVendorAttribute();
        $response['currentBookings'] = $this->getMyCurrentBookingSameDayCheckinDateRecords();
        $response['availableSeats'] = $this->getMyAvailableTotalRentedSeatsRecords();
        return $response;
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

    

    public function submitUserProfileVerificationData($request)
    {
        if (!auth()->user()->is_profileVerifired()) {
            $userProfileVerify = auth()->user()->userCompleteProfileVerifired();
            $userProfileVerify->create($request->all());
            $response['status_code'] = 200;
            $response['type'] = 'success';
            $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
            $response['message'] = 'Profile Verification request send successfully, It will take 2-3 days for review your documets';
        } else {
            $userProfileVerify = auth()->user()->userCompleteProfileVerifired;
            if ($userProfileVerify->status == 'pending') {
                $response['status_code'] = 200;
                $response['type'] = 'warning';
                $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                $response['message'] = 'Profile Verification request already in progess';
            } elseif ($userProfileVerify->status == 'approved') {
                $response['status_code'] = 200;
                $response['type'] = 'warning';
                $response['url'] = route(RouteServiceProvider::VENDOR_HOME_ROUTE);
                $response['message'] = 'Your profile already verified by admin';
            }
        }
        return $response;
    }

    public function updateUserProfileDetails($request)
    {
        $filleable = $request->only('name');
        if ($request->dob) {
            $filleable['dob'] = date("Y-m-d", strtotime($request->dob));
        }
        if ($request->image) {
            $filleable['image'] = $request->image;
        }

        if (auth()->user()->update($filleable)) {
            if (auth()->user()->userCompleteProfileVerifired) {
                if ($request->logo_image) {
                    $profileVerify = auth()->user()->userCompleteProfileVerifired;
                    $profileVerify->logo_image = $request->logo_image;
                    $profileVerify->save();
                }
            }
            return ['status_code' => 200, 'type' => 'success', 'message' => trans('flash.success.user_updated_successfully'), 'url' => route('vendor.dashboard.myprofile')];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong'), 'url' => route('vendor.dashboard.myprofile')];
        }
    }

    public function deactivateAccount($request)
    {
        auth()->user()->deactivate_at = now();
        if (auth()->user()->save()) {
            session()->flush();
            Auth::logout();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Account Deactivated Successfully', 'url' => route('vendor.login'), 'modelClose' => 'deactivateAccount'];
        } else {
            return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
        }
    }

    public function deleteAccount($request)
    {
        $bookingCount = $this->Booking->where('vendor_id', auth()->user()->id)->whereIn('status', ['in-progress', 'pending', 'request', 'confirmed'])->count();
        $scheduleVisitCount = $this->ScheduleVisit->whereHas('scheduleVisitProperty.property', function ($query) {
            $query->where("user_id", auth()->user()->id);
        })->where('status', 'request')->count();

        if ($bookingCount > 0 || $scheduleVisitCount > 0) {
            return ['status_code' => 200, 'type' => 'error', 'message' => "You can't delete your account. Your Property in progress."];
        } else {
            $user = auth()->user();
            if (auth()->user()->delete()) {
                $this->EmailNotificationsRepo->sendDeleteAccountMailandNotification($user);
                session()->flush();
                Auth::logout();
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Account deleted successfully', 'url' => route('vendor.login'), 'modelClose' => 'deleteAcoount'];
            } else {
                return ['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_something_went_wrong')];
            }
        }
    }
    public function storePropertyOffer($request)
    {
        $chk = $this->PropertyOffers->where('property_id', $request->property_id)->where('coupon_id', $request->coupon_id)->first();
        if ($chk) {
            $chk->delete();
            return ['status_code' => 200, 'type' => 'success', 'message' => 'Offer removed successfully', 'url' => route('vendor.myproperty'), 'modelClose' => 'applyOffer'];
        } else {
            $coupon = Coupon::where(['status' => 1, 'id' => $request->coupon_id])->first();
            if ($coupon && !$coupon->is_global_apply) {
                $insert['property_id'] = $request->property_id;
                $insert['coupon_id'] = $request->coupon_id;
                $this->PropertyOffers->create($insert);
                return ['status_code' => 200, 'type' => 'success', 'message' => 'Offer applied successfully', 'url' => route('vendor.myproperty'), 'modelClose' => 'applyOffer'];
            } else {
                return ['status_code' => 200, 'type' => 'error', 'message' => 'Coupon Code not allowed, This Code already applicable globally', 'url' => route('vendor.myproperty'), 'modelClose' => 'applyOffer'];
            }
        }
    }

    public function getRecord($id)
    {
        return $this->Property->find($id);
    }


    public function getRecordForDelete($id)
    {
        $property =  $this->Property->with(['bookings' =>  function ($q) {
            $q->where('status', 'pending');
        }])->where('id', $id)->first();
        return $property;
    }

    public function getAllPropertyTypes()
    {
        return  PropertyType::where('status', 1)->get();
    }

    public function getAllCoupons($request)
    {
        $coupon = Coupon::where(['status' => 1, 'is_global_apply' => 0, 'property_type_id' => $request->property_type_id])->get();

        foreach ($coupon as  $val) {
            $val->image = $val->PicturePath;
            $appliedOffers = PropertyOffers::where('property_id', $request->property_id)->where('coupon_id', $val->id)->count();
            if ($appliedOffers > 0) {
                $val->is_offer_applied = 1;
            } else {
                $val->is_offer_applied = 0;
            }
        }
        return $coupon;
    }

    public function getAllMyPropertyRecord($request)
    {
        $property = $this->Property->where('user_id', auth()->user()->id)->with(['propertyType', 'propertyRooms', 'propertyAmenities', 'propertyOffers', 'city']);
        $to  = date("Y-m-d", strtotime($request->get('to') . "+1 day"));
        $from  = date("Y-m-d", strtotime($request->get('from')));
        if (!empty($from)) {
            $property = $property->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $property = $property->where('created_at', '<=', $to);
        }

        if ($request->get('property_type')) {
            $property_type = $request->get('property_type');
            $property = $property->whereHas('propertyType', function ($query) use ($property_type) {
                $query->where("property_types.slug", $property_type);
            });
        }

        return $property->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function getAllMyBookingsRecord($request)
    {
        $type  = ($request->get('type')) ? $request->get('type') : 'all';
        $bookings = $this->Booking->where('vendor_id', auth()->user()->id)->whereNotIn('status', ['in-progress', 'pending', 'request']);
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

    public function getMyCurrentBookingSameDayCheckinDateRecords()
    {
        return $this->Booking->where('vendor_id', auth()->user()->id)->where('status', 'confirmed')->count();
    }

    public function getMyAvailableTotalRentedSeatsRecords()
    {

        $allSeats = $this->Property->where([['user_id', auth()->user()->id]])->where(['is_publish' => true, 'status' => 'publish'])
            ->select(
                DB::raw('SUM(rented_seats) as total_rented_seats'),
                \DB::raw(
                    "SUM(CASE WHEN  rented_seats IS NULL THEN 1 ELSE 0 END) AS flat_homestay_seats"
                )
            )
            ->get()->toArray();
        $totalRentedSeats =  $allSeats[0]['total_rented_seats'] + $allSeats[0]['flat_homestay_seats'];
        $totalBookedSeats = $this->Booking->where([['vendor_id', auth()->user()->id]])->whereIn('status', ['confirmed', 'pending'])->where('check_in_date', now())->get();

        $x = 0;
        $y = 0;
        $guests_pg = 0;
        $guests_single = 0;
        $guests_pg2 = 0;
        $guests_single2 = 0;
        foreach ($totalBookedSeats as $bookedSeat) {
            if ($bookedSeat->booked_by == 'company') {
                if (in_array($bookedSeat->property->propertyType->slug, ['hostel-pg', 'hostel-pg-one-day', 'guest-hotel'])) {
                    $guests_pg2 += json_decode($bookedSeat->property_booking_data)->total_guests ?? 0;
                } else {
                    $guests_single2 = $y;
                    $y++;
                }
            } else {

                if (in_array($bookedSeat->property->propertyType->slug, ['hostel-pg', 'hostel-pg-one-day'])) {
                    $guests_pg += json_decode($bookedSeat->property_booking_data)->guests;
                } else {
                    $guests_single = $x;
                    $x++;
                }
            }
        }
        $reservedSeats = $guests_pg + $guests_pg2 + $guests_single2 + $guests_single;
        $availableSeats = $totalRentedSeats - $reservedSeats;
        return $availableSeats < 0 ? 0 : $availableSeats;
    }

    public function MyBookingsDetails($request, $slug)
    {
        $data = $this->Booking->where('slug', $slug)->with(['Property'])->first();
        return $data;
    }

    public function mypropertyChangeStatus($request)
    {
        $property = $this->getRecord($request->get('id'));
        if ($property) {
            $id = $property->id;
            $change = $this->Property->find($id);
            $publish = (int) $change->is_publish;
            if ($publish == 0) {
                $update_arr = array('is_publish' => true);
                $message = 'Property Published successfully';
                $this->Property->where('id', $id)->update($update_arr);
            } else {
                $update_arr = array('is_publish' => false);
                $this->Property->where('id', $id)
                    ->update($update_arr);
                $message = 'Property Un-published successfully';
            }
            $type = 'success';
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'warning';
        }

        $response['status'] = true;
        $response['is_search'] = true;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }

    public function mypropertyUploadSelfieMedia($request)
    {
        $filename = uploadWithResize($request->file('files'), 'property/' . auth()->id() . '/');
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['status_code'] = 200;
        return $response;
    }

    public function mypropertyUploadAgreementMedia($request)
    {
        $filename = uploadOnS3Bucket($request->file('files'), 'property/' . auth()->id() . '/');
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['status_code'] = 250;
        return $response;
    }

    public function updateUploadSelfieOrAgreement($request)
    {
        try {
            $filleable = $request->only('upload_selfie,upload_agreement');
            $record = $this->getRecord($request->get('property_id'));
            if ($request->get('upload_selfie')) {
                $update_arr = array('upload_selfie' => $request->get('upload_selfie'), 'status_selfie' => 'pending');
                $modalId = 'upload_selfie';
            } else {
                $update_arr = array('upload_agreement' => $request->get('upload_agreement'), 'status_agreement' => 'pending');
                $modalId = 'upload_agrement';
            }

            $record->update($update_arr);

            $response['status'] = true;
            $response['upload_agreement'] = true;
            $response['message'] = 'Uploaded successfully';
            $response['type'] = 'success';
            $response['modelClose'] = $modalId;
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->Property->destroy($id);
    }

    public function getAllMyVisitRecord($request)
    {
        $myvisits = $this->ScheduleVisit->whereHas('scheduleVisitProperty.property', function ($query) {
            $query->where("user_id", auth()->user()->id);
        })->whereIn('status', ['confirmed', 'cancelled']);
        if ($request->get('type')) {
            if ($request->get('type') == 'past_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date",  '<', date("Y-m-d"));
                });
            } else if ($request->get('type') == 'upcoming_visit') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date",  '>', date("Y-m-d"));
                });
            } else if ($request->get('type') == 'active') {
                $myvisits = $myvisits->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '=', date("Y-m-d"));
                });
            }
        }
        return $myvisits->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function myvisitDetailsRecord($visit_slug)
    {
        $myvisitData = $this->ScheduleVisit->whereHas('scheduleVisitProperty', function ($query) {
            $query->where("schedule_visit_property.user_id", auth()->user()->id);
        })->with("scheduleVisitProperty")->with(['scheduleVisitProperty', 'scheduleVisitProperty.property'])->where('slug', $visit_slug)->whereIn('status', ['confirmed', 'cancelled'])->first();
        return $myvisitData;
    }

    public function getBookingRequests($request)
    {
        $bookingRequests = $this->Booking->where('vendor_id', auth()->user()->id)->whereHas('property', function ($query) {
            $query->where("user_id", auth()->user()->id);
        })->where('status', 'pending');
        return $bookingRequests->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function acceptBookingRequest($bookingid)
    {
        $booking = $this->Booking::where('id', $bookingid)->first();
        $property = $this->Property->where('id', $booking->property_id)->with(['propertyType'])->withTrashed()->first();
        $room_occupancy_type = isset($booking->BookingJsonData['room_occupancy_type']) ? $booking->BookingJsonData['room_occupancy_type'] : '';
        $property_room_type = isset($booking->BookingJsonData['property_room_type']) ? $booking->BookingJsonData['property_room_type'] : '';
        if ($booking->booked_by == 'company') {
            $request =   $booking->BookingJsonData;
            $currentBookingGuests = 0;
        } else {
            $currentBookingGuests = isset(json_decode($booking->property_booking_data)->guests) ? json_decode($booking->property_booking_data)->guests : 1;

            $request = [];
        }

        $error = $this->FrontendBookingRepository->getAvailableSeatsbyPropertyID($booking->property_id, $booking->check_in_date, $booking->check_out_date ?? '', $room_occupancy_type, $property_room_type, '', $property, $request, $booking->booked_by, $booking->id, $currentBookingGuests);

        if ($error) return $error;
        if ($booking->status == 'pending') {
            $booking->status = $booking::CONFIRMED;
            $booking->booking_confirmed_date = now();
            if ($booking->save()) {
                $filleable['title'] = 'Booking Confirmation';
                $filleable['body'] = 'booking has been confirm';
                $filleable['type'] = ' booking';
                $filleable['slug'] =  $booking->slug;
                $user =  $booking->customer;
                if ($user) {
                    $tokenss =  $user;
                }
                sendPushNotificationForBooking($filleable, $tokenss);
                $this->EmailNotificationsRepository->sendBookingConfirmedEmailForUser($booking);
                $this->EmailNotificationsRepository->sendBookingConfirmedEmailForVendor($booking);

                $response['message'] = 'Booking confirmed Successfully';
                $response['type'] = 'success';
                $response['status_code'] = 200;
                $response['refresh'] = 'true';
                $response['show_msg'] = 'true';
                $response['reloadPage'] = true;
            } else {
                $response['message'] = 'Booking not confirmed please try again later';
                $response['type'] = 'error';
                $response['status_code'] = 400;
                $response['show_msg'] = 'true';
            }
            return $response;
        } else {
            $response['message'] = 'Booking not confirmed please try again later';
            $response['type'] = 'error';
            $response['status_code'] = 400;
            $response['show_msg'] = 'true';
        }
        return $response;
    }

    public function rejectBookingRequest($bookingid)
    {
        $booking = $this->Booking::find($bookingid);
        $booking->status = $booking::REJECTED;
        $booking->booking_reject_date = now();
        if ($booking->save()) {
            $this->EmailNotificationsRepository->sendBookingRejectedEmailForUser($booking);
            $this->EmailNotificationsRepository->sendBookingRejectedEmailForVendor($booking);

            $response['message'] = 'Booking rejected Successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            $response['refresh'] = 'true';
            $response['reloadPage'] = true;
            $response['show_msg'] = 'true';
        } else {
            $response['message'] = 'Booking not rejected please try again later';
            $response['type'] = 'error';
            $response['status_code'] = 400;
            $response['show_msg'] = 'true';
        }
        return $response;
    }

    public function getAllPropertiesForMyEarningsFilter()
    {
        return Property::where('user_id', auth()->user()->id)->whereHas('bookings', function ($query) {
        })->where('status', 'publish')->pluck('property_name', 'id')->toArray();
    }

    public function getMyPaymentsEarningHistory($request)
    {
        $payments = $this->getMyPaymentsTotalEarningsRecords($request);
        $commission = $this->Booking->whereIn('payment_id', $payments->pluck('id'))->where('vendor_id', auth()->user()->id)->whereIn('status', ['confirmed', 'completed'])->sum('commission');
        $response['totalEarnings'] = numberformatWithCurrency($payments->sum('amount') - $commission);
        $response['totalBookings'] = $payments->count();
        $response['records'] = $payments->paginate(\config::get('custom.default_pagination'));
        return $response;
    }

    public function getMyPaymentsTotalEarningsRecordsNew($request)
    {
        $bookings = $this->Booking->where('vendor_id', auth()->user()->id)->whereNotIn('status', Booking::notAcceptedStatusInEarning);
        if ($request->get('property_type')) {
            $bookings =   $bookings->where('property_id', $request->get('property_type'));
        }

        $bookings = $bookings->whereHas('payment', function ($query) use ($request) {
            if ($request->get('type') != 'all' && $request->get('type')) {
                $query->where('method', $request->get('type'));
            }
            $to  = $request->get('to');
            $from  = $request->get('from');
            if (!empty($from)) {
                $from  = date("Y-m-d", strtotime($from));
                $query->where('created_at', '>=', $from);
            }
            if (!empty($to)) {
                $to  = date("Y-m-d", strtotime($to . "+1 day"));
                $query->where('created_at', '<=', $to);
            }
        });

        return $bookings;
    }

    public function getMyPaymentsTotalEarningsRecords($request)
    {
        $payments = $this->Payment::orderBy('created_at', 'desc')->where('type', 'Booking')->with(['user', 'Booking', 'Booking.property'])->whereHas('Booking', function (Builder $q) use ($request) {
            $q->where('bookings.vendor_id', auth()->user()->id);
            $q->whereNotIn('bookings.status', Booking::notAcceptedStatusInEarning);
            if ($request->get('property_type')) {
                $q->where('bookings.property_id', $request->get('property_type'));
            }
        });
        if ($request->get('type') != 'all' && $request->get('type')) {
            $payments = $payments->where('method', $request->get('type'));
        }
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

    public function getAllReviewsRecord($request)
    {
        $reviews = Review::where('object_model', 'property')->whereHas('property', function ($query) {
            $query->where("user_id", auth()->user()->id);
        });

        return $reviews->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function vendorLogout($request)
    {
        $redirectRoute = RouteServiceProvider::LOGIN_ROUTE;
        if (auth()->user()->hasRole('customer')) {
            $redirectRoute = RouteServiceProvider::LOGIN_ROUTE;
        } elseif (auth()->user()->hasRole('vendor')) {
            $redirectRoute = RouteServiceProvider::VENDOR_LOGIN_ROUTE;
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('success', trans('flash.success.your_account_logged_out_successfully'));
        return redirect()->route($redirectRoute);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
