<?php

namespace Modules\Booking\Repositories\Frontend;

use App\Models\User;
use Modules\Property\Entities\Property;
use Modules\Booking\Entities\Booking;
use Modules\Coupon\Entities\Coupon;
use Modules\Property\Entities\PropertyOffers;
use Modules\Property\Entities\PropertyRooms;

class CompanyBookingRepository implements BookingRepositoryInterface
{

    protected $model = 'Property';
    protected $bookingClass;
    protected $propertyClass;

    function __construct()
    {
        $this->propertyClass = Property::class;
        $this->bookingClass = Booking::class;
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
            return $this->sendSuccess(
                $data
            );
        }

        if ($ammount >= $commission_ammount) {
            $data['type'] = 'error';
            $data['message'] = "Offer code cannot be applied.";
            return $this->sendSuccess(
                $data
            );
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
            $ammount = $amount * ($propertyType->commission / 100);
            $commmission = $ammount;
        }
        return $commmission;
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
        if (!auth()->user()->hasRole('company')) {
            $response['message'] = trans('Only company can book any property');
            $response['type'] = 'error';
            return $response;
        }
    }
}
