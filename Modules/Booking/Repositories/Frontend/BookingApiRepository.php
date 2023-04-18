<?php

namespace Modules\Booking\Repositories\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Modules\Property\Entities\Property;
use Modules\Booking\Entities\Booking;
use Modules\Coupon\Entities\Coupon;
use Modules\Property\Entities\PropertyRooms;
use Modules\Payment\Repositories\Frontend\RozarPay\RozarPayPaymentRepository;
use Modules\Booking\Repositories\Frontend\BookingRepository as BookingFrontRepo;


class BookingApiRepository implements BookingRepositoryInterface
{
    protected $model = 'Property';
    protected $bookingClass;
    public $BookingFrontRepo;
    public $propertyClass;
    public $RozarPayPaymentRepository;

    function __construct(RozarPayPaymentRepository $RozarPayPaymentRepository, BookingFrontRepo $BookingFrontRepo)
    {
        $this->propertyClass = Property::class;
        $this->bookingClass = Booking::class;
        $this->RozarPayPaymentRepository = $RozarPayPaymentRepository;
        $this->BookingFrontRepo = $BookingFrontRepo;
    }


    public function getRecordBySlug($slug)
    {
        return $this->bookingClass::where('slug', $slug)->first();
    }

    public function getUserCheckRole()
    {
        //Check customer role
        if (!auth()->user()->hasRole('customer')) {
            $response['message'] = trans('Only customer can book any property');
            $response['type'] = 'error';
            return $response;
        }
    }

    public function getRecordForBooking($slug)
    {
        $record =  $this->propertyClass::where('slug', $slug)->with(['propertyAmenities', 'propertyRooms', 'propertyPaymentInfo'])->first();
        if ($record) {
            $record['amenities_id'] = json_decode($record['amenities_ids']);
            $rooms = [];
            if (!empty($record->propertyRooms)) {
                foreach ($record->propertyRooms as $key => $propRoom) {
                    $rooms['room_type'][] = $propRoom->room_type;
                    $record[$propRoom->room_type] = array_filter(
                        array(
                            'is_ac' => $propRoom->is_ac,
                            'is_non_ac' => $propRoom->is_non_ac,
                            'ac_total_seats' => $propRoom->ac_total_seats,
                            'ac_rented_seats' => $propRoom->ac_rented_seats,
                            'ac_amount' => $propRoom->ac_amount,
                            'non_ac_total_seats' => $propRoom->non_ac_total_seats,
                            'non_ac_rented_seats' => $propRoom->non_ac_rented_seats,
                            'non_ac_amount' => $propRoom->non_ac_amount,
                            'ac_is_food_included' => $propRoom->ac_is_food_included,
                            'non_ac_is_food_included' => $propRoom->non_ac_is_food_included,
                        )
                    );
                }
                if (!empty($rooms['room_type'])) {
                    $record['room_type'] = $rooms['room_type'];
                }
            }
        }
        return $record;
    }

    public function getRoomOptions($room_id)
    {
        $roomData = PropertyRooms::find($room_id);
        return $roomData;
    }

    public function addToCart($request)
    {
        $agent_corp_code_req = $request->is_discount_applied && $request->offer_code == '' ? 'required' : '';
        $offer_code_req = $request->is_discount_applied && $request->agent_corp_code == '' ? 'required' : '';

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'email' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'check_in_date' => 'required',
            'per_room_amount' => 'required',
            'property_slug' => 'required',
            'property_type_slug' => 'required',
            'booking_payment_type' => 'required',
            'check_out_date' => 'required_if:is_checkout_date,true,1',
            'guests' => 'required_if:is_guest_type,true,1',
            'adult' => 'required_if:is_guest_type,false,0',
            'children' => 'required_if:is_guest_type,false,0',
            'room_occupancy_type' => 'required_if:is_occupancy_type,true,1',
            'room_type_id' => 'required_if:is_occupancy_type,true,1',
            'property_room_type' => 'required_if:is_occupancy_type,true,1',
            'bhk' => 'required_if:property_type_slug,flat',
            'is_discount_applied' => 'required',
            'discount' => 'required_if:is_discount_applied,true,1',
            'discount_value' => 'required_if:is_discount_applied,true,1',
            'discount_type' => 'required_if:is_discount_applied,true,1',
            'offer_code' => $offer_code_req,
            'agent_corp_code' => $agent_corp_code_req,
            'is_global_offer_applied' => $offer_code_req,
            'final_amount_after_selection' => 'required',
            'is_checkout_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        $property = $this->propertyClass::where('slug', $request->property_slug)->with(['propertyType'])->withTrashed()->first();
        $response = $this->BookingFrontRepo->addToCartValidate($request, $property, 'api');
        if (!($response)) {
            $commission_ammount = $this->BookingFrontRepo->applyAdminCommisionBooking($property->propertyType, $request->amount);
            if ($request->is_global_offer_applied == 1) {
                $commission_ammount = $commission_ammount - $request->discount;
                if ($commission_ammount < 0) {
                    $commission_ammount = 0;
                }
            }
            $check_in_date_carbon = new \Carbon\Carbon($request->check_in_date);
            $check_in_date = $check_in_date_carbon->format('Y-m-d H:i:s');

            if ($request->check_out_date) {
                $check_out_date_carbon = new \Carbon\Carbon($request->check_out_date);
                $check_out_date = $check_out_date_carbon->format('Y-m-d H:i:s');
            }

            if ($request->agent_corp_code) {
                $insert['agent_corp_code'] = $request->agent_corp_code ?? null;
                $insert['code_type'] = $request->code_type ?? null;
            }

            $insert['status'] = 'in-progress';
            $insert['property_id'] = $property->id;
            $insert['vendor_id'] = $property->user_id;
            $insert['user_id'] = auth()->id();
            $insert['property_room_id'] = $request->room_type ?? null;
            $insert['amount'] = $request->amount;
            $insert['booked_by'] = 'customer';

            // Total = $request->amount- $request->discount;(Amount to be paid by User)
            $insert['total'] = $request->final_amount_after_selection;
            $insert['booking_payment_type'] = $request->booking_payment_type;
            $insert['final_offer_amount'] =  $request->discount ?? 0;
            $total = $request->amount - $insert['final_offer_amount'];
            if ($request->booking_payment_type == 'partial') {
                $insert['remaining_payable_amount'] = $total - $request->final_amount_after_selection;
                $insert['is_remaining_amount_paid'] = 'no';
                $insert['commission'] = $request->final_amount_after_selection;
            } else {
                $insert['remaining_payable_amount'] = 0;
                $insert['is_remaining_amount_paid'] = null;
                $insert['commission'] = $commission_ammount;
            }
            $insert['commission_type'] = $property->propertyType->commission_type;
            $insert['check_in_date'] = $check_in_date ?? null;
            $insert['check_out_date'] = $check_out_date ?? null;

            if (in_array($property->propertyType->slug, ['guest-hotel', 'hostel-pg-one-day', 'homestay'])) {
                $insert['custom_chekout_date'] = $check_out_date ?? null;
            } else {
                $custom_chekout_date = $check_in_date_carbon->addDays(30)->format('Y-m-d H:i:s');
                $insert['custom_chekout_date'] = $custom_chekout_date ?? null;
            }
            if (in_array($property->propertyType->slug, ['guest-hotel', 'hostel-pg-one-day', 'hostel-pg'])) {
                // HOSTEL-PG SEATS FOR CUSTOMER
                $insert['single_ac_seats'] = $request->room_occupancy_type == 'single' && $request->property_room_type == 'AC' ? $request->guests : 0;
                $insert['single_non_ac_seats'] = $request->room_occupancy_type == 'single' && $request->property_room_type == 'NON-AC'  ? $request->guests : 0;
                $insert['double_ac_seats'] = $request->room_occupancy_type == 'double' && $request->property_room_type == 'AC' ? $request->guests : 0;
                $insert['double_non_ac_seats'] = $request->room_occupancy_type == 'double' && $request->property_room_type == 'NON-AC'  ? $request->guests : 0;
                $insert['triple_ac_seats'] = $request->room_occupancy_type == 'triple' && $request->property_room_type == 'AC' ? $request->guests : 0;
                $insert['triple_non_ac_seats'] = $request->room_occupancy_type == 'triple' && $request->property_room_type == 'NON-AC'  ? $request->guests : 0;
                $insert['quadruple_ac_seats'] = $request->room_occupancy_type == 'quadruple' && $request->property_room_type == 'AC' ? $request->guests : 0;
                $insert['quadruple_non_ac_seats'] = $request->room_occupancy_type == 'quadruple' && $request->property_room_type == 'NON-AC'  ? $request->guests : 0;
                // HOTEL SEATS FOR CUSTOMER
                $insert['standard_ac_seats'] = $request->room_occupancy_type == 'standard' && $request->property_room_type == 'AC' ? 1 : 0;
                $insert['standard_non_ac_seats'] = $request->room_occupancy_type == 'standard' && $request->property_room_type == 'NON-AC'  ? 1 : 0;
                $insert['deluxe_ac_seats'] = $request->room_occupancy_type == 'deluxe' && $request->property_room_type == 'AC' ? 1 : 0;
                $insert['deluxe_non_ac_seats'] = $request->room_occupancy_type == 'deluxe' && $request->property_room_type == 'NON-AC'  ? 1 : 0;
                $insert['suite_ac_seats'] = $request->room_occupancy_type == 'suite' && $request->property_room_type == 'AC' ? 1 : 0;
                $insert['suite_non_ac_seats'] = $request->room_occupancy_type == 'suite' && $request->property_room_type == 'NON-AC'  ? 1 : 0;
            }
            $insert['property_booking_data'] = json_encode($request->all());
            $insert['name'] = $request->name;
            $insert['email'] = $request->email;
            $insert['phone'] = $request->phone;
            if ($request->slug) {
                $booking = $this->bookingClass::where('slug', $request->slug)->first();
                $booking->update($insert);
            } else {
                $booking = $this->bookingClass::create($insert);
            }


            if ($booking) {
                $bookingCode = str_pad($booking->id, 4, '0', STR_PAD_LEFT);
                $booking->code = 'OLE' . $bookingCode;
                $booking->save();
                $order_arr = [
                    'bookingtype' => 'Booking',
                    'amount' => $insert['total'],
                    'request_id' => $booking->id,
                ];
                $response['order_data'] = $this->RozarPayPaymentRepository->orderIdGenerate((object)$order_arr);
                $response['status_code'] = 200;
                $response['message'] = 'Please complete payment for booking property successfully';
                $response['data'] =  $booking;
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Something went wrong.';
            }
        }

        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function applyOfferCode($request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'property_slug' => 'required',
            'offer_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $offerCode = $request->offer_code;
        $property_slug = $request->property_slug;
        $property =  $this->propertyClass::where('slug', $property_slug)->first();
        $offerDetails = Coupon::where(['coupon_code' => $offerCode, 'status' => 1])->first();
        $error = $this->BookingFrontRepo->applyCouponValidate($offerDetails, $property);
        if ($error) return $error;

        if ($offerDetails->offer_type == 'Flatrate') {
            $ammount =  $offerDetails->amount;
            if ($ammount >= $request->amount) {
                $data['type'] = 'error';
                $data['status_code'] = 200;
                $data['message'] = "Offer amount must be lower than total amount.";
            } else {
                $data = array(
                    'status_code' => 200,
                    'type' => 'success',
                    'message' => 'Offer code applied successfully',
                    'discount' => $ammount,
                    'discount_type' => $offerDetails->offer_type,
                    'discount_value' => $offerDetails->amount,
                );
            }
        } else {
            $ammount = round($request->amount * ($offerDetails->amount / 100), 2);
            $data = array(
                'status_code' => 200,
                'type' => 'success',
                'message' => 'Offer code applied successfully',
                'discount' => $ammount,
                'discount_type' => $offerDetails->offer_type,
                'discount_value' => $offerDetails->amount,
            );
        }

        return response()->json($data, $data['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($data['status_code']);
    }

    public function applyAgentCode($request)
    {
        $validator = Validator::make($request->all(), [
            'agent_corp_code' => 'required',
            'property_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        $CodeData = User::where('status', 1)->where(function ($query) use ($request) {
            $query->where('agent_code', $request->agent_corp_code);
            $query->orWhere('company_code', $request->agent_corp_code);
        })->first();
        $error = $this->BookingFrontRepo->agentCorpCommissionValidate($CodeData);
        if ($error) return $error;

        $code_type = $CodeData->agent_code != '' ? 'agent' : 'company';
        $discountPercent = $code_type == 'agent' ? setting_item('forntend-user-discount-percentage-agent') : setting_item('forntend-user-discount-percentage-company');
        $ammount = round($request->property_price * ($discountPercent / 100));
        $data = array(
            'message' => ucfirst($code_type) . ' code applied successfully',
            'discount_amount' => $ammount,
            'discount_type' => 'Percentage',
            'discount_deduct' => $discountPercent,
            'code_type' => $code_type,
            'status_code' => 200,
            'type' => 'success',
        );


        return response()->json($data, $data['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($data['status_code']);
    }
}
