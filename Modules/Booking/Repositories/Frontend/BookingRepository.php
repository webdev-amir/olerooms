<?php

namespace Modules\Booking\Repositories\Frontend;

use App\Models\User;
use Carbon\Carbon;
use Modules\Property\Entities\Property;
use Modules\Booking\Entities\Booking;
use Illuminate\Support\Facades\Auth;
use Modules\Coupon\Entities\Coupon;
use Modules\Settings\Repositories\SettingsRepositoryInterface as SettingsRepositoryInterface;
use Modules\Property\Entities\PropertyOffers;
use Modules\Property\Entities\PropertyRooms;

class BookingRepository implements BookingRepositoryInterface
{

    protected $model = 'Property';
    protected $propertyClass;
    protected $bookingClass;
    protected $SettingsRepository;

    function __construct(SettingsRepositoryInterface $SettingsRepositoryInterface)
    {
        $this->propertyClass = Property::class;
        $this->bookingClass = Booking::class;
        $this->SettingsRepository = $SettingsRepositoryInterface;
    }

    public function getRecordBySlug($slug)
    {
        return $this->bookingClass::where('slug', $slug)->first();
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

    public function getAvailableSeatsbyPropertyID($propertyID, $check_in_date = '', $check_out_date = '', $room_occupancy_type = '', $property_room_type = '', $requestGuests = 0, $property, $request = [], $booking_user_type = 'customer', $booking_id = 0, $currentBookingGuests = 0)
    {
        $response = [];
        $availableSeats = 0;
        $bookings = $this->bookingClass::where(['property_id' => $propertyID])->whereIn('status', ['pending', 'confirmed']);
        if ($check_in_date == '') {
            $check_in_date = now();
        } else {
            $check_in_date = new \Carbon\Carbon($check_in_date);
        }

        if ($booking_id != 0) {
            $bookings->where('id', '!=', $booking_id);
        }

        $bookings->where(function ($query) use ($property, $check_in_date, $check_out_date) {
            if (in_array($property->propertyType->slug, ['hostel-pg', 'flat'])) {
                $query->whereRaw('DATEDIFF("' . $check_in_date . '", check_in_date) < 30');
            }
            if ($check_out_date != '') {
                $check_out_date = new \Carbon\Carbon($check_out_date);
                if ($check_out_date == $check_in_date) {
                    $check_out_date = $check_out_date->addDays(1);
                }
                $in_out_diff = $check_out_date->diffInDays($check_in_date);
                $query->whereRaw('DATEDIFF("' . $check_in_date . '", check_in_date) <' . $in_out_diff);
                $query->orWhere(function ($subquery) use ($check_in_date, $check_out_date) {
                    $subquery->where('check_in_date', '<=', $check_in_date);
                    $subquery->where('check_out_date', '>=', $check_out_date);
                });
            }
        });

        // Check Homestay and Flat Availabilty

        if (in_array($property->propertyType->slug, ['flat', 'homestay'])) {
            $bookings = $bookings->first();
            if ($bookings) {
                $response['message'] = 'Your selected property already booked for these dates.';
                $response['type'] = 'error';
                $response['status_code'] = 400;
                $response['show_msg'] = 'true';
                $response['availableSeats'] = 0;
                $availableSeats = 0;
            } else {
                $availableSeats = 1;
                $response = [];
            }
        } else {
            $property = $this->propertyClass::where('id', $propertyID)->withTrashed();
            if ($booking_user_type == 'company' && !empty($request)) {
                $property = $property->with('propertyRooms');
                $property = $property->first();
                $seatsArray = array();

                $roomsType = ($property->propertyType->slug == 'guest-hotel') ? config('custom.hotel_room_types') : config('custom.hostelpg_room_types');
                foreach ($roomsType as $roomTypeSingle) {
                    $field_ac = $roomTypeSingle . '_ac_seats';
                    $field_non_ac = $roomTypeSingle . '_non_ac_seats';
                    $seatsArray[$roomTypeSingle]['ac'] = $bookings->sum($field_ac) > 0 ? $bookings->sum($field_ac) : 0;
                    $seatsArray[$roomTypeSingle]['non_ac'] = $bookings->sum($field_non_ac) > 0 ? $bookings->sum($field_non_ac) : 0;
                }

                foreach ($property->propertyRooms as $propertyRoomSingle) {
                    if (isset($seatsArray[$propertyRoomSingle->room_type]['ac']) && isset($request['guests_com'][$propertyRoomSingle->room_type]['ac']) && $request['guests_com'][$propertyRoomSingle->room_type]['ac'] > 0) {
                        $Diff1 = $propertyRoomSingle->ac_rented_seats - $seatsArray[$propertyRoomSingle->room_type]['ac'];

                        if ($Diff1 < 1 && $propertyRoomSingle->ac_rented_seats > 0) {
                            $response['message'] = 'All ' . ucfirst($propertyRoomSingle->room_type) . ' (AC) rooms are booked.';
                            $response['type'] = 'warning';
                            $response['status_code'] = 400;
                            $response['show_msg'] = 'true';
                            return $response;
                        }

                        if ($Diff1 < $request['guests_com'][$propertyRoomSingle->room_type]['ac']) {
                            $response['message'] = 'Only ' . $Diff1 . ' ' . ucfirst($propertyRoomSingle->room_type) . ' (AC) rooms are left.';
                            $response['type'] = 'warning';
                            $response['status_code'] = 400;
                            $response['show_msg'] = 'true';
                            return $response;
                        }
                    }
                    if (isset($seatsArray[$propertyRoomSingle->room_type]['non_ac']) && isset($request['guests_com'][$propertyRoomSingle->room_type]['non_ac']) && $request['guests_com'][$propertyRoomSingle->room_type]['non_ac'] > 0) {
                        $Diff = $propertyRoomSingle->non_ac_rented_seats - $seatsArray[$propertyRoomSingle->room_type]['non_ac'];


                        if ($Diff < 1 && $propertyRoomSingle->non_ac_rented_seats > 0) {
                            $response['message'] = 'All ' . ucfirst($propertyRoomSingle->room_type) . ' (NON-AC) rooms are booked.';
                            $response['type'] = 'warning';
                            $response['status_code'] = 400;
                            $response['show_msg'] = 'true';
                            return $response;
                        }


                        if ($Diff < $request['guests_com'][$propertyRoomSingle->room_type]['non_ac']) {
                            $response['message'] = 'Only ' . $Diff . ' ' . ucfirst($propertyRoomSingle->room_type) . ' (NON-AC) rooms are left.';
                            $response['type'] = 'warning';
                            $response['status_code'] = 400;
                            $response['show_msg'] = 'true';
                            return $response;
                        }
                    }
                }
            } else {

                if ($room_occupancy_type != '') {
                    $property->with('propertyDynamicRoomType', function ($query) use ($room_occupancy_type) {
                        $query->where('room_type', $room_occupancy_type);
                    });
                }
                $property = $property->first();

                if ($room_occupancy_type != '' && $property_room_type != '') {
                    $roomDetails = $property->propertyDynamicRoomType;
                    $availableSeats = $property_room_type == 'AC' ? $roomDetails->ac_rented_seats ?? $roomDetails['ac_rented_seats'] : $roomDetails->non_ac_rented_seats ?? $roomDetails['non_ac_rented_seats'];
                } else {
                    $availableSeats = $property->rented_seats;
                }
                $guests = 0;
                if ($property_room_type == 'NON-AC') {
                    $property_room_type = 'non_ac';
                } else {
                    $property_room_type = 'ac';
                }
                $field = $room_occupancy_type . '_' . $property_room_type . '_seats';
                if ($bookings->sum($field) > 0) {
                    $guests = $bookings->sum($field);
                }
                $availableSeats = $availableSeats - $guests;
                if ($availableSeats < 1) {
                    $response['message'] = 'Your selected property have no rooms available on these dates.';
                    $response['type'] = 'warning';
                    $response['status_code'] = 400;
                    $response['show_msg'] = 'true';
                    return $response;
                }
                if ($requestGuests > 0) {
                    if ($availableSeats < $requestGuests) {
                        $response['message'] = 'Only ' . $availableSeats . ' seats are available now.';
                        if ($availableSeats == 1) {
                            $response['message'] = 'Only ' . $availableSeats . ' seat is available now.';
                        }
                        $response['type'] = 'warning';
                        $response['status_code'] = 400;
                        $response['show_msg'] = 'true';
                        return $response;
                    }
                } else {
                    if ($availableSeats < $currentBookingGuests) {
                        $response['message'] = 'Only ' . $availableSeats . ' seats are available now.';
                        $response['type'] = 'error';
                        $response['status_code'] = 400;
                        $response['show_msg'] = 'true';
                        $response['guest_count'] = $currentBookingGuests;
                        return $response;
                    }
                }
            }
        }

        return $response;
    }


    public function getRoomOptions($request)
    {
        $roomData = PropertyRooms::find($request->room_id);
        return $roomData;
    }

    public function applyAgentCode($request)
    {
        $CodeData = User::where('status', 1)->where(function ($query) use ($request) {
            $query->where('agent_code', $request->agent_corp_code);
            $query->orWhere('company_code', $request->agent_corp_code);
        })->first();
        $error = $this->agentCorpCommissionValidate($CodeData);
        if ($error) return $error;
        $code_type = $CodeData->agent_code != '' ? 'agent' : 'company';
        $discountPercent = $code_type == 'agent' ? setting_item('forntend-user-discount-percentage-agent') : setting_item('forntend-user-discount-percentage-company');
        $ammount = round($request->property_price * ($discountPercent / 100));
        $data = array(
            'type' => 'success',
            'message' => ucfirst($code_type) . ' code applied successfully',
            'discount_amount' => $ammount,
            'discount_type' => 'Percentage',
            'discount_deduct' => $discountPercent,
            'code_type' => $code_type,
        );

        return $data;
    }

    public function agentCorpCommissionValidate($CodeData)
    {

        if (empty($CodeData)) {
            $data['type'] = 'error';
            $data['message'] = 'Invalid Agent/Corporate Code.';
            return $this->sendSuccess(
                $data
            );
        }

        if ($CodeData->id == auth()->user()->id) {
            $data['type'] = 'error';
            $data['message'] = 'Cannot use your own corporate code.';
            return $this->sendSuccess(
                $data
            );
        }
    }

    public function applyOfferCode($request)
    {
        $offerCode = $request->offerCode;
        $property_id = $request->property_id;
        $property = Property::where('id', $property_id)->first();
        $offerDetails = Coupon::where(['coupon_code' => $offerCode, 'status' => 1])->first();

        $error = $this->applyCouponValidate($offerDetails, $property);
        if ($error) return $error;

        if ($offerDetails->offer_type == 'Flatrate') {
            $ammount =  $offerDetails->amount;
        } else {
            $ammount = round($request->property_price * ($offerDetails->amount / 100), 2);
        }
        $commission_ammount = $this->applyAdminCommisionBooking($property->propertyType, $request->property_price);

        if ($ammount >= $request->property_price) {
            $data['type'] = 'error';
            $data['message'] = "Offer amount must be lower than total amount.";
            return $this->sendSuccess($data);
        }

        if ($ammount >= $commission_ammount && $offerDetails->is_global_apply == 1) {
            $data['type'] = 'error';
            $data['message'] = "Offer code cannot be applied.";
            return $this->sendSuccess($data);
        }

        $data = array(
            'type' => 'success',
            'message' => 'Offer code applied successfully',
            'discount_amount' => $ammount,
            'discount_type' => $offerDetails->offer_type,
            'discount_deduct' => $offerDetails->amount,
            'is_global_coupon' => $offerDetails->is_global_apply == 1 ? 1 : 0
        );

        return $data;
    }

    public function addToCart($request)
    {
        $property = $this->propertyClass::where('id', $request->property_id)->with(['propertyType'])->withTrashed()->first();
        $error = $this->addToCartValidate($request, $property);
        if ($error) return $error;
        $commission_ammount = $this->applyAdminCommisionBooking($property->propertyType, $request->amount);

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
        $insert['property_id'] = $request->property_id;
        $insert['vendor_id'] = $property->user_id;
        $insert['user_id'] = Auth::id();
        $insert['property_room_id'] = $request->room_type ?? null;
        $insert['amount'] = $request->amount;
        $insert['booked_by'] = $request->booking_user_type ?? 'customer';
        // Total = $request->amount- $request->discount;(Amount to be paid by User)
        $insert['total'] = $request->final_amount_after_selection;
        $insert['booking_payment_type'] = $request->booking_payment_type;
        if ($request->booking_payment_type == 'partial') {
            $insert['remaining_payable_amount'] = $request->total - $request->final_amount_after_selection;
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
        $insert['final_offer_amount'] =  $request->discount ?? 0;

        if (in_array($property->propertyType->slug, ['guest-hotel', 'hostel-pg-one-day', 'homestay'])) {
            $insert['custom_chekout_date'] = $check_out_date ?? null;
        } else {
            $custom_chekout_date = $check_in_date_carbon->addDays(30)->format('Y-m-d H:i:s');
            $insert['custom_chekout_date'] = $custom_chekout_date ?? null;
        }
        if (in_array($property->propertyType->slug, ['guest-hotel', 'hostel-pg-one-day', 'hostel-pg'])) {
            if ($request->booking_user_type == 'company') {
                $insert['single_ac_seats'] =  isset($request->guests_com['single']['ac']) ? $request->guests_com['single']['ac'] : 0;
                $insert['single_non_ac_seats'] = isset($request->guests_com['single']['non_ac']) ? $request->guests_com['single']['non_ac'] : 0;
                $insert['double_ac_seats'] = isset($request->guests_com['double']['ac']) ? $request->guests_com['double']['ac'] : 0;
                $insert['double_non_ac_seats'] = isset($request->guests_com['double']['non_ac']) ? $request->guests_com['double']['non_ac'] : 0;
                $insert['triple_ac_seats'] = isset($request->guests_com['triple']['ac']) ? $request->guests_com['triple']['ac'] : 0;
                $insert['triple_non_ac_seats'] = isset($request->guests_com['triple']['non_ac']) ? $request->guests_com['triple']['non_ac'] : 0;
                $insert['quadruple_ac_seats'] = isset($request->guests_com['quadruple']['ac']) ? $request->guests_com['quadruple']['ac'] : 0;
                $insert['quadruple_non_ac_seats'] = isset($request->guests_com['quadruple']['non_ac']) ? $request->guests_com['quadruple']['non_ac'] : 0;
                $insert['standard_ac_seats'] = isset($request->guests_com['standard']['ac']) ? $request->guests_com['standard']['ac'] : 0;
                $insert['standard_non_ac_seats'] = isset($request->guests_com['standard']['non_ac']) ? $request->guests_com['standard']['non_ac'] : 0;
                $insert['deluxe_ac_seats'] = isset($request->guests_com['deluxe']['ac']) ? $request->guests_com['deluxe']['ac'] : 0;
                $insert['deluxe_non_ac_seats'] = isset($request->guests_com['deluxe']['non_ac']) ? $request->guests_com['deluxe']['non_ac'] : 0;
                $insert['suite_ac_seats'] = isset($request->guests_com['suite']['ac']) ? $request->guests_com['suite']['ac'] : 0;
                $insert['suite_non_ac_seats'] = isset($request->guests_com['suite']['non_ac']) ? $request->guests_com['suite']['non_ac'] : 0;
            } else {

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
            $url = $booking->booked_by == 'customer' ? route('booking.showPayment', $booking->slug) : $url = route('company.booking.showPayment', $booking->slug);;

            return $this->sendSuccess([
                'url' => $url,
                'booking_code' => $booking->slug,
            ], 'Please complete payment for booking property successfully');
        }
        return $this->sendError(__("Can not check availability"));
    }

    public function addToCartValidate($request, $property, $type = 'request')
    {
        if (empty($property)) {
            $data['type'] = 'error';
            $data['message'] = 'Property not available contact support.';
            $data['status_code'] = 400;
            if ($type == 'api') {
                return $data;
            }
            return $this->sendSuccess(
                $data
            );
        }

        if (!$property->author->is_profileVerifiredApproved()) {
            $data['type'] = 'error';
            $data['message'] = trans('Sorry! you cannot book this property, Property owner is not verified');
            $data['status_code'] = 400;
            if ($type == 'api') {
                return $data;
            }
            return $this->sendSuccess(
                $data
            );
        }

        // Check property status
        if (!$property->isBookable()) {
            $data['type'] = 'error';
            $data['message'] = trans('Property is not bookable');
            $data['status_code'] = 400;
            if ($type == 'api') {
                return $data;
            }
            return $this->sendSuccess(
                $data
            );
        }

        // Check Property Availabilty
        if ($property->bookings->isNotEmpty()) {
            $data = $this->getAvailableSeatsbyPropertyID($property->id, $request->check_in_date, $request->check_out_date ?? '', $request->room_occupancy_type, $request->property_room_type,  $request->guests, $property, $request, $request->booking_user_type);


            if ($type == 'api') {
                return $data;
            }
            if ($data) {
                return $this->sendSuccess(
                    $data
                );
            }
        }


        if ($property && in_array($property->propertyType->slug, ['hostel-pg', 'hostel-pg-one-day', 'guest-hotel'])) {
            if ($request->booking_user_type == 'company') {
                foreach ($request->guests_com as $key => $guestcom) {
                    $room_vacancy = PropertyRooms::where(['room_type' => $key, 'property_id' => $request->property_id])->first();
                    if ($guestcom['ac'] > 0) {
                        $seats = $room_vacancy->ac_rented_seats;
                        if ($seats < $request->guests) {
                            $data['type'] = 'error';
                            $data['message'] = 'Only ' . $seats . ' seats are available now.';
                            $data['status_code'] = 400;
                            if ($type == 'api') {
                                return $data;
                            }
                            return $this->sendSuccess(
                                $data
                            );
                        }
                    }
                    if ($guestcom['non_ac'] > 0) {
                        $seats = $room_vacancy->non_ac_rented_seats;
                        if ($seats < $request->guests) {
                            $data['type'] = 'error';
                            $data['message'] = 'Only ' . $seats . ' seats are available now.';
                            $data['status_code'] = 400;
                            if ($type == 'api') {
                                return $data;
                            }
                            return $this->sendSuccess(
                                $data
                            );
                        }
                    }
                }
            } else {
                // Check Hostel PG Guests Availabilty Before No Booking Happened yet.
                $property = $this->propertyClass::where('id', $property->id)->with('propertyDynamicRoomType', function ($query) use ($request) {
                    $query->where('room_type', $request->room_occupancy_type);
                })->first();
                $seats = $request->property_room_type == 'AC' ? $property->propertyDynamicRoomType->ac_rented_seats : $property->propertyDynamicRoomType->non_ac_rented_seats;
                if ($property->propertyType->slug == 'guest-hotel') {
                    $guests = 1;
                } else {
                    $guests = $request->guests;
                }
                if ($seats < $guests) {
                    $data['type'] = 'error';
                    $data['message'] = 'Only ' . $seats . ' seats are available now.';
                    $data['status_code'] = 400;
                    if ($type == 'api') {
                        return $data;
                    }
                    return $this->sendSuccess(
                        $data
                    );
                }
            }
        }
    }


    public function applyCouponValidate($offerDetails, $property)
    {


        if (empty($offerDetails)) {
            $data['type'] = 'error';
            $data['message'] = "Offer Code not valid";
            return $this->sendSuccess(
                $data
            );
        }
        if ($offerDetails->start_date > now()->format('Y-m-d')) {
            $data['type'] = 'error';
            $data['message'] = "Offer Code Invalid";
            return $this->sendSuccess(
                $data
            );
        }

        if ($offerDetails->end_date < now()->format('Y-m-d')) {
            $data['type'] = 'error';
            $data['message'] = "Offer Code Expired";
            return $this->sendSuccess(
                $data
            );
        }

        if ($offerDetails->is_global_apply == 1) {
            return false;
        }

        if (empty(PropertyOffers::where(['property_id' => $property->id, 'coupon_id' => $offerDetails->id])->first())) {
            $data['type'] = 'error';
            $data['message'] = "Offer Code not valid for this property. Contact Owner!";
            return $this->sendSuccess(
                $data
            );
        }
    }


    public function applyAdminCommisionBooking($propertyType, $amount)
    {
        if ($propertyType->commission_type == 'flatrate') {
            $commmission =  $propertyType->commission;
        } else {
            $commmission = $amount * ($propertyType->commission / 100);
        }
        return round($commmission);
    }

    public function bookingDeleteBeforePayment($booking)
    {
        $booking->delete();
        $data['url']  = route('customer.dashboard.mybooking');
        $data['message'] = "Booking cancelled successfully!";
        $data['type'] = 'success';
        return $data;
    }

    public function sendError($message, $data = [])
    {
        $data['status'] = 0;
        $data['redirect'] = @$message['redirect'];
        return $this->sendSuccess($data, $message);
    }

    public function sendSuccess($data = [], $message = '')
    {
        if (is_string($data)) {
            return response()->json([
                'message' => $data,
                'status' => true
            ]);
        }
        if (!isset($data['status'])) $data['status'] = 1;

        if ($message)
            $data['message'] = $message;

        return response()->json($data);
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
}
