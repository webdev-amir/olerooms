<?php

namespace Modules\Api\Http\Controllers\Customer\Property\Booking;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Property\Booking\BookingRepository;


class BookingController extends Controller
{
    public $BookingRepo;

    public function __construct(BookingRepository $BookingRepo, Request $request)
    {
        $this->BookingRepo = $BookingRepo;
        if ($request->headers->get('IsGguest') == 'false')
            $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    public function cancelBooking(Request $request)
    {
        $response = $this->BookingRepo->cancelBooking($request);
        return $response;
    }

    public function bookingPropertyDetail(Request $request)
    {
        $response = $this->BookingRepo->bookingPropertyDetail($request);
        return $response;
    }

    public function getBookingList(Request $request)
    {
        $response = $this->BookingRepo->getBookingList($request);
        return $response;
    }

    public function reviewBooking(Request $request)
    {
        $response = $this->BookingRepo->reviewBooking($request);
        return $response;
    }

    public function deleteBooking(Request $request)
    {
        $response = $this->BookingRepo->deleteBooking($request);
        return $response;
    }
}
