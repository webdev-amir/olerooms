<?php

namespace Modules\Api\Repositories\Customer\Property\Booking;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use Modules\Review\Entities\Review;
use Modules\Payment\Entities\Payment;
use Modules\Property\Entities\Property;
use Modules\PropertyType\Entities\PropertyType;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;
use Modules\Booking\Entities\Booking;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepository;
use Modules\Roles\Entities\Role;

class BookingRepository implements BookingRepositoryInterface
{
    public $User;
    public $Role;
    public $Property;
    public $PropertyType;
    public $Review;
    public $ScheduleVisit;
    public $ScheduleVisitProperty;
    public $Booking;
    public $EmailNotificationsRepository;
    public $paymentClass;


    function __construct(Role $Role, User $User, Property $Property, Review $Review, PropertyType $PropertyType,  ScheduleVisit $ScheduleVisit, ScheduleVisitProperty $ScheduleVisitProperty, EmailNotificationsRepository $EmailNotificationsRepository, Booking $Booking)
    {
        $this->User = $User;
        $this->Role = $Role;
        $this->Property = $Property;
        $this->PropertyType = $PropertyType;
        $this->Review = $Review;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->ScheduleVisitProperty = $ScheduleVisitProperty;
        $this->Booking = $Booking;
        $this->paymentClass = Payment::class;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
    }

    public function bookingPropertyDetail($request)
    {
        $validator = Validator::make($request->all(), [
            'booking_slug' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $booking = $this->Booking->where('slug', $request->booking_slug)->with(['property', 'review'])->first();

            if ($booking) {
                $response['status_code'] = 200;
                $response['message'] = 'visit list';
                $response['booking']['booking_id'] = $booking->id;
                $response['booking']['booking_code'] = $booking->code;
                $response['booking']['booking_status'] = $booking->status;
                $response['booking']['cancel_request_date'] = $booking->cancel_request_date;
                $response['booking']['can_send_cancel_request'] = !in_array($booking->status, ['completed', 'rejected']) && $booking->status != 'cancelled' && $booking->CancellationBeforeDate == true && isset($booking->payment) ? true : false;
                $response['booking']['is_review'] = $booking->review != null ? true  : false;
                $response['booking']['total_booking_amount'] = numberformatWithCurrency($booking->total) ?? '';
                $jsonData = json_decode($booking->property_booking_data);

                if (!empty($booking->property->propertyAvailableFor)) {
                    foreach ($booking->property->propertyAvailableFor as $key => $propertyAvailableFor) {
                        $response['booking']['property']['available_for'][$key] = $propertyAvailableFor->available_for;
                    }
                }

                if ($booking->property->propertyAmenities) {
                    foreach ($booking->property->propertyAmenities as $key => $amenity) {
                        $response['booking']['property']['amenities'][$key]['images'] = $amenity->amenities->PicturePath;
                        $response['booking']['property']['amenities'][$key]['name'] = $amenity->amenities->name;
                    }
                }

                $response['booking']['property']['id'] = $booking->property->id;
                $response['booking']['property']['code'] = $booking->property->property_code;
                $response['booking']['property']['name'] = $booking->property->property_name;
                $response['booking']['property']['rating'] = $booking->property->RatingAverage;
                $response['booking']['property']['image'] = $booking->property->CoverImg;
                $response['booking']['property']['lat'] = $booking->property->lat;
                $response['booking']['property']['long'] = $booking->property->long;
                $response['booking']['property']['map_location'] = $booking->property->map_location;
                $response['booking']['property']['booking_date'] = ($booking->created_at) ?? '';
                $response['booking']['property']['check_in_date'] = ($booking->check_in_date) ?? '';
                if (in_array($booking->property->propertyType->slug, ['guest-hotel', 'hostel-pg-one-day', 'homestay'])) {
                    $response['booking']['property']['check_out_date'] = ($booking->check_out_date) ?? '';
                }
                $response['booking']['property']['property_type'] = $booking->property->propertyType->name;
                $response['booking']['property']['furnished_type'] = $booking->property->FurnishedTypeValue;
                $response['booking']['property']['total_seats'] = $booking->property->total_seats;
                $response['booking']['property']['owner_convenient_time'] = $booking->property->convenient_time;
                $response['booking']['customer']['name'] = $booking->user->name;
                $response['booking']['customer']['email'] = $booking->user->email;
                $response['booking']['customer']['image'] = $booking->user->PicturePath;
                $response['booking']['customer']['phone'] = $booking->user->phone;
                if (in_array($booking->property->propertyType->slug, ['hostel-pg', 'hostel-pg-one-day', 'homestay'])) {
                    $response['booking']['customer']['guests'] = $jsonData->guests;
                } else {
                    $response['booking']['customer']['adult'] = $jsonData->adult;
                    $response['booking']['customer']['children'] = $jsonData->children;
                }

                if (in_array($booking->property->propertyType->slug, ['hostel-pg', 'hostel-pg-one-day', 'guest-hotel'])) {
                    $response['booking']['customer']['room_type'] =  ucfirst($jsonData->room_occupancy_type) . ' (' . $jsonData->property_room_type . ')';
                } else {
                    $response['booking']['customer']['room_type'] = '';
                }


                if (isset($booking->payment)) {
                    $response['booking']['transaction']['id'] = isset($booking->payment->transaction_id) ? $booking->payment->transaction_id : 'N/A';
                    $response['booking']['transaction']['amount'] = isset($booking->payment->amount) ? numberformatWithCurrency($booking->payment->amount) : 'N/A';
                    $response['booking']['transaction']['payment_mode'] = isset($booking->payment->method) ? $booking->payment->method : 'N/A';
                    $response['booking']['transaction']['payment_date'] = isset($booking->payment->created_at) ? $booking->payment->created_at : 'N/A';
                    $response['booking']['transaction']['discount'] = isset($jsonData->discount) ?  numberformatWithCurrency($jsonData->discount) : '';
                    $response['booking']['transaction']['discount_code'] = isset($jsonData->discount) && $jsonData->discount > 0 ?  ($booking->agent_corp_code != '' ? $booking->agent_corp_code : $jsonData->offer_code) : '';

                    $response['booking']['transaction']['total_amount'] = isset($booking->amount) ?  numberformatWithCurrency($booking->amount) : '';
                    $response['booking']['transaction']['remaining_payable_amount'] = numberformatWithCurrency($booking->remaining_payable_amount) ?? '';
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'No booking found.';
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

    public function cancelBooking($request)
    {
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required',
            'cancel_reason' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        $booking = $this->Booking->where('code', $request->booking_code)->first();


        if ($booking) {
            if ($booking->status == 'cancelled') {
                $response['message'] = 'Booking already cancelled!';
                $response['status_code'] = 200;
            } elseif ($booking->CancellationBeforeDate == false) {
                $response['message'] = 'Your booking already checked-In , Can not be cancelled now';
                $response['status_code'] = 200;
            } elseif ($booking->cancel_request_date) {
                $response['message'] = 'Already requested for cancellation';
                $response['status_code'] = 200;
            } else {
                $booking->cancel_request_date = now();

                $booking->cancellation_reason = $request->cancel_reason;
                $booking->booking_cancelled_reject_date = NULL;
                $booking->save();
                $response['message'] = 'Cancel request submitted successfully!';
                $response['status_code'] = 200;
                $this->EmailNotificationsRepository->sendBookingCancellationRequestEmail(auth()->user(), $booking);

                $this->EmailNotificationsRepository->sendBookingCancellationRequestEmailToVendor(auth()->user(), $booking);
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'no booking found with this bookingcode';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function getBookingList($request)
    {
        try {
            $type = ($request->get('type')) ? $request->get('type') : 'all';
            $bookings = $this->Booking->with('property')->where('user_id', auth()->user()->id)->whereNotIn('status', ['in-progress', 'request']);

            if ($request->get('property_type')) {
                $property_type = $request->get('property_type');
                $bookings = $bookings->whereHas('Property.propertyType', function ($query) use ($property_type) {
                    $query->where("property_types.slug", $property_type);
                });
            }

            if ($type == 'active') {
                $bookings = $bookings->where('check_in_date', now()->format("Y-m-d"))->whereNotIn('status', ['cancelled', 'rejected']);
            } else if ($type == 'upcoming') {
                $bookings = $bookings->where('check_in_date', '>', now()->format("Y-m-d"))->whereNotIn('status', ['cancelled', 'rejected']);
            } else  if ($type == "previous") {
                $bookings = $bookings->whereDate('check_in_date', '<', Carbon::now());
            } else if ($type != 'all') {
                $bookings = $bookings->where('status', $type);
            }

            $to = date("Y-m-d", strtotime($request->get('to') . "+1 day"));
            $from = date("Y-m-d", strtotime($request->get('from')));
            if (!empty($from)) {
                $bookings = $bookings->where('created_at', '>=', $from);
            }
            if (!empty($to)) {
                $bookings = $bookings->where('created_at', '<=', $to);
            }
            $bookings = $bookings->orderBy('id', 'desc')->paginate(10);
            $response = paginationFormat($bookings);
            $response['property_types'] = $this->getAllPropertyTypes();
            if (count($bookings) > 0) {
                $response['status_code'] = 200;
                $response['message'] = 'booking list';

                foreach ($bookings as $key => $booking) {
                    $response['property'][$key]['booking_status'] = $booking->status;
                    $response['property'][$key]['cancel_request_date'] = $booking->cancel_request_date;
                    $response['property'][$key]['booking_id'] = $booking->id;
                    $response['property'][$key]['booking_slug'] = $booking->slug;
                    $response['property'][$key]['type'] = $booking->property->propertyType->name;
                    $response['property'][$key]['property_code'] = $booking->property->property_code;
                    $response['property'][$key]['booking_code'] = $booking->code;
                    $response['property'][$key]['propertyid'] = $booking->property->id;
                    $response['property'][$key]['slug'] = $booking->property->slug;
                    $response['property'][$key]['name'] = $booking->property->property_name;
                    $response['property'][$key]['status'] = $booking->property->status;
                    $response['property'][$key]['paid_amount'] = numberformatWithCurrency($booking->total);
                    $response['property'][$key]['remaining_amount'] = numberformatWithCurrency($booking->remaining_payable_amount);
                    $response['property'][$key]['image'] = $booking->property->CoverImg;
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'no data found';
                $response['property'] = array();
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
    public function getAllPropertyTypes()
    {
        return  PropertyType::where('status', 1)->get();
    }

    public function reviewBooking($request)
    {
        $validator = Validator::make($request->all(), [
            'rate_number' => 'required',
            'content' => 'max:255',
            'object_id' => 'required',
            'booking_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $id = $request->id;
            $reviewReply =  $this->Review->where('object_id', $request->object_id)
                ->where('booking_id', $request->booking_id)->first();
            if (!$reviewReply) {
                $filleable = $request->only('user_id', 'object_id', 'rate_number', 'content', 'booking_id');
                $filleable['object_model'] = 'property';
                $filleable['user_id'] = auth()->id();
                $filleable['author_ip'] = request()->ip();
                $filleable['publish_date'] = Carbon::now();
                $filleable['status'] = 'publish';

                $this->Review->fill($filleable);
                $this->Review->save();
                $booking = Booking::find($request->booking_id);
                $this->EmailNotificationsRepository->sendReviewNotificationMailVendor($booking);
                $this->EmailNotificationsRepository->sendReviewMailAdmin($booking);
                $response['status_code'] = 200;
                $response['message'] = 'review added sucessfully';
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'review already present';
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

    public function deleteBooking($request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'property_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $booking = $this->Booking->where('property_id', $request->property_id)
                ->where('id', $request->booking_id)->first();
            if ($booking) {
                $booking->delete();
                $response['status_code'] = 200;
                $response['message'] = 'Booking removed successfuly';
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Booking id not valid';
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
}
