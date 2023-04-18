<?php

namespace Modules\Booking\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Booking\Repositories\Frontend\CompanyBookingRepository as CompanyBookingRepo;
use Modules\Booking\Repositories\Frontend\BookingRepository as BookingRepo;
use Illuminate\Support\Facades\Session;

class CompanyBookingController extends Controller
{
    protected $CompanyBookingRepo;
    protected $BookingRepo;

    public function __construct(CompanyBookingRepo $CompanyBookingRepo, BookingRepo $BookingRepo)
    {
        $this->CompanyBookingRepo = $CompanyBookingRepo;
        $this->BookingRepo = $BookingRepo;
    }

    public function bookingDetails($slug)
    {
        $error = $this->CompanyBookingRepo->getUserCheckRole();
        if ($error) {
            Session::flash($error['type'], $error['message']);
            return back();
        }
        $property = $this->CompanyBookingRepo->getRecordForBooking($slug);
        if ($property) {
            if ($property->author->is_profileVerifiredApproved()) {
                return view('booking::frontend.index_company', compact('property'));
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

    public function bookingPayment($slug)
    {
        $booking = $this->CompanyBookingRepo->getRecordBySlug($slug);
        if (!empty($booking) && $booking->status == 'in-progress') {
            $bookingJsonData = json_decode($booking->property_booking_data);
            return view('booking::frontend.index_company', compact('booking', 'bookingJsonData'));
        }
        Session::put('error', 'Sorry! Booking payment already done');
        abort(404);
    }



    public function applyAgentCode(Request $request)
    {
        $response = $this->CompanyBookingRepo->applyAgentCode($request);
        return $response;
    }

    public function applyOfferCode(Request $request)
    {
        $response = $this->CompanyBookingRepo->applyOfferCode($request);
        return $response;
    }

   

    public function propertyBookingPaymentSuccess(Request $request, $slug)
    {
        $bookingDetail = $this->CompanyBookingRepo->getRecordBySlug($slug);
        if ($bookingDetail) {
            $bookingDetailJsonData = json_decode($bookingDetail->property_booking_data);
            return view('booking::frontend.company_payment_successfull_details', compact('bookingDetail', 'bookingDetailJsonData'));
        }
        Session::put('error', 'Sorry! Booking not available');
        abort(404);
    }
}
