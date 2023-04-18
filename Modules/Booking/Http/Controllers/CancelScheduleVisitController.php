<?php

namespace Modules\Booking\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\PropertyType\Repositories\PropertyTypeRepositoryInterface as PropertyType;
use Modules\Booking\Repositories\Backend\CancelScheduleVisitRepository as CancelScheduleVisitRepo;

class CancelScheduleVisitController extends Controller
{
    protected $model = 'Booking';
    public function __construct(PropertyType $PropertyType, CancelScheduleVisitRepo $CancelScheduleVisitRepo)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->PropertyType = $PropertyType;
        $this->CancelScheduleVisitRepo = $CancelScheduleVisitRepo;
    }

    public function index(Request $request)
    {
        $vendors = $this->CancelScheduleVisitRepo->getVendors();
        $propertyTypes = $this->PropertyType->getAllRecord();
        if(request()->ajax()) 
        {
            return $this->CancelScheduleVisitRepo->getAjaxData($request);
        }
        return view('booking::backend.cancelschedulevisit.index',compact('vendors','propertyTypes'))->withModel(strtolower($this->model));
    }

    public function show($slug)
    {
        $data = $this->CancelScheduleVisitRepo->getRecordBySlug($slug);
        if (empty($data)) {
            Session::flash('error', 'Visit not found.');
            return redirect()->route('booking.cancelschedulevisit.index');
        }
        $jsonData = json_decode($data->property_booking_data);
        return view('booking::backend.cancelschedulevisit.show', compact('data', 'jsonData'));
    }

    public function status(Request $request,$slug)
    {
        $response = $this->CancelScheduleVisitRepo->changeStatus($request,$slug);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back(); 
        
    }
}
