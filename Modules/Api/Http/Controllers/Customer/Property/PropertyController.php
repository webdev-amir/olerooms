<?php

namespace Modules\Api\Http\Controllers\Customer\Property;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Property\PropertyRepository as PropertyRepo;
use Modules\Booking\Repositories\Frontend\BookingApiRepository as BookingRepo;


class PropertyController extends Controller
{
    public $PropertyRepo;
    public $BookingRepo;

    public function __construct(Request $request, PropertyRepo $PropertyRepo, BookingRepo $BookingRepo)
    {
        $this->PropertyRepo = $PropertyRepo;
        $this->BookingRepo = $BookingRepo;
        if ($request->headers->get('IsGguest') == 'false')
            $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    public function addFavorite(Request $request)
    {
        $response = $this->PropertyRepo->addFavorite($request);
        return $response;
    }

    public function myWishlist(Request $request)
    {
        $response = $this->PropertyRepo->myWishlist($request);
        return $response;
    }

    public function propertyDetail(Request $request)
    {
        $response = $this->PropertyRepo->propertyDetail($request);
        return $response;
    }
    public function propertyReviewListing(Request $request)
    {
        $response = $this->PropertyRepo->propertyReviewListing($request);
        return $response;
    }

    public function scheduleGetOrderId(Request $request)
    {
        $response = $this->PropertyRepo->scheduleGetOrderId($request);
        return $response;
    }

    public function makePayment(Request $request)
    {
        $response = $this->PropertyRepo->makePayment($request);
        return $response;
    }

    public function bookingDetails(Request $request)
    {
        $error = $this->BookingRepo->getUserCheckRole();
        if ($error) {
            $response['status_code'] = 402;
            $response['message'] = $error['message'];
        } else {
            $property = $this->BookingRepo->getRecordForBooking($request->property_slug);

            if ($property && $property->author->is_profileVerifiredApproved()) {
                if ($property->author->userCompleteProfileVerifired && $property->author->ComponyLogo != '' && config('custom.is_company_logo_show')) {
                    $response['data']['company_logo'] = $property->author->ComponyLogo;
                } else {
                    $response['data']['company_logo'] = '';
                }
                $response['status_code'] = 200;
                $response['data']['property_code'] = $property->property_code;
                $response['data']['is_show_partial_payment_option'] = $property->propertyType->is_partial;
                $response['data']['property_slug'] = $property->slug;
                $response['data']['image'] = $property->CoverImg;
                $response['data']['property_type'] = $property->propertyType->name;
                $response['data']['property_type_slug'] = $property->propertyType->slug;
                $response['data']['furnished_type'] = $property->furnished_type;
                $response['data']['total_seats'] = $property->total_seats;
                $response['data']['property_type_commission'] = $property->propertyType->commission;

                $response['data']['rating'] = $property->RatingAverage;
                foreach ($property->propertyAvailableFor as $key => $available) {
                    $response['data']['available_for'][$key] = $available->available_for;
                }
                $offers1 = [];
                $offers2 = [];

                foreach ($property->propertyOffers as  $offer) {
                    $offers1[] =  $response['data']['available_offers'][] = $offer->coupon;
                }

                foreach ($property->propertyType->propertyGlobalOffers as  $offer) {
                    $offers2[] =  $response['data']['available_offers'][] = $offer;
                }

                if (!empty($offers1) && !empty($offers2)) {
                    $response['data']['available_offers']  = array_merge($offers2, $offers1);
                }

                $response['data']['security_deposit_amount'] = $property->security_deposit_amount;
                if (in_array($property->propertyType->slug, ['flat', 'homestay'])) {
                    $response['data']['rooms'] = [];
                    $response['data']['is_occupancy_type'] = false;
                } else {
                    foreach ($property->propertyRooms as $key => $value) {
                        $response['data']['available_rooms'][$key] = $value;
                    }
                    $response['data']['is_occupancy_type'] = true;
                }
                $response['data']['flat_amount_per_month'] = $property->propertyType->slug == 'flat' ? $property->amount : null;
                $response['data']['flat_bhk'] = $property->propertyType->slug == 'flat' ? str_replace('bhk', '', $property->bhk_type) : null;
                $response['data']['homestay_amount_per_day'] = $property->propertyType->slug ==  'homestay' ? $property->amount : null;
                $response['data']['homestay_max_guests'] = $property->propertyType->slug ==  'homestay' ? $property->guest_capacity : null;
                $response['data']['is_checkout_date'] = in_array($property->propertyType->slug, ['hostel-pg', 'flat']) ? false : true;
                $response['data']['is_guest_type'] = in_array($property->propertyType->slug, ['guest-hotel', 'flat']) ? false : true;
            } else {
                $response['status_code'] = 402;
                $response['message'] = 'Sorry! you cannot book this property, Property owner is not verified';
            }
        }


        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function addBooking(Request $request)
    {
        $response = $this->BookingRepo->addToCart($request);
        return $response;
    }

    public function applyOfferCode(Request $request)
    {
        $response = $this->BookingRepo->applyOfferCode($request);
        return $response;
    }

    public function applyAgentCode(Request $request)
    {
        $response = $this->BookingRepo->applyAgentCode($request);
        return $response;
    }

    public function getRoomOptions(Request $request)
    {
        $response = $this->BookingRepo->getRoomOptions($request);
        return $response;
    }
}
