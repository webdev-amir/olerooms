<?php

namespace Modules\Booking\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\PropertyType\Repositories\PropertyTypeRepositoryInterface as PropertyType;
use Modules\Booking\Repositories\Backend\CancelBookingRepositoryInterface as CancelBookingRepo;

class CancelBookingController extends Controller
{
    protected $model = 'Booking';
    public function __construct(PropertyType $PropertyType, CancelBookingRepo $CancelBookingRepo)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->PropertyType = $PropertyType;
        $this->CancelBookingRepo = $CancelBookingRepo;
    }

    public function index(Request $request)
    {
        $vendors = $this->CancelBookingRepo->getVendors();
        $propertyTypes = $this->PropertyType->getAllRecord();
        if(request()->ajax()) 
        {
            return $this->CancelBookingRepo->getAjaxData($request);
        }
        return view('booking::backend.cancelbooking.index',compact('vendors','propertyTypes'))->withModel(strtolower($this->model));
    }

    public function show($slug)
    {
        $booking = $this->CancelBookingRepo->getRecordBySlug($slug);
        if (empty($booking)) {
            Session::flash('error', 'Booking not found.');
            return redirect()->route('booking.cancelbooking.index');
        }
        $jsonData = json_decode($booking->property_booking_data);
        return view('booking::backend.cancelbooking.show', compact('booking', 'jsonData'));
    }

    public function status(Request $request,$slug)
    {
        $response = $this->CancelBookingRepo->changeStatus($request,$slug);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back(); 
        
    }
}
