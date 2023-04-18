<?php

namespace Modules\Booking\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Booking\Repositories\Frontend\BookingRepository as BookingRepo;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{

    protected  $BookingRepo;

    public function __construct(BookingRepo $BookingRepo)
    {
        $this->BookingRepo = $BookingRepo;
    }

    public function bookingDetails($slug)
    {
        $error = $this->BookingRepo->getUserCheckRole();
        if ($error) {
            Session::flash($error['type'], $error['message']);
            return back();
        }

        $property = $this->BookingRepo->getRecordForBooking($slug);
        if ($property) {
            if ($property->author->is_profileVerifiredApproved()) {
                return view('booking::frontend.index', compact('property'));
            }
            Session::put('error', 'Sorry! you cannot booked this property, Property owner is not verified');
            abort(404);
        }
        abort(404);
    }

    public function addBooking(Request $request)
    {
        $response = $this->BookingRepo->addToCart($request);
        return $response;
    }

    public function bookingDeleteBeforePayment($slug)
    {
        $booking = $this->BookingRepo->getRecordBySlug($slug);
        if ($booking) {
            $response = $this->BookingRepo->bookingDeleteBeforePayment($booking);
            return $response;
        }
        Session::put('error', 'Sorry! No booking record found!');
        abort(404);
    }


    public function bookingPayment($slug)
    {
        $booking = $this->BookingRepo->getRecordBySlug($slug);
        if (!empty($booking) && $booking->status == 'in-progress') {
            $bookingJsonData = json_decode($booking->property_booking_data);
            return view('booking::frontend.index', compact('booking', 'bookingJsonData'));
        }
        Session::put('error', 'Sorry! Booking payment already done');
        abort(404);
    }

    public function applyAgentCode(Request $request)
    {
        $response = $this->BookingRepo->applyAgentCode($request);
        return $response;
    }

    public function applyOfferCode(Request $request)
    {
        $response = $this->BookingRepo->applyOfferCode($request);
        return $response;
    }

    public function getRoomOptions(Request $request)
    {
        $response = $this->BookingRepo->getRoomOptions($request);
        return $response;
    }

    public function propertyBookingPaymentSuccess(Request $request, $slug)
    {
        $bookingDetail = $this->BookingRepo->getRecordBySlug($slug);
        if ($bookingDetail) {
            $bookingDetailJsonData = json_decode($bookingDetail->property_booking_data);
            return view('booking::frontend.payment_successfull_details', compact('bookingDetail', 'bookingDetailJsonData'));
        }
        Session::put('error', 'Sorry! Booking not available');
        abort(404);
    }
}
