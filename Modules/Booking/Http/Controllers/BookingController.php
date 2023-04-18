<?php

namespace Modules\Booking\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use App\Http\Requests\UploadImagePdfRequest;
use Modules\Booking\Http\Requests\SubmitCompleteProfileRequest;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\PropertyOwnerDashboard\Repositories\Frontend\MyDashboard\MyDashboardRepositoryInterface as MyDashboardRepository;
use Modules\Booking\Repositories\Backend\BookingRepositoryInterface as BookingRepo;
use Modules\PropertyType\Repositories\PropertyTypeRepositoryInterface as PropertyType;

class BookingController extends Controller
{
    protected $model = 'Booking';
    public function __construct(PropertyType $PropertyType, BookingRepo $BookingRepo,CommonRepo $CommonRepo, MyDashboardRepository $MyDashboardRepository)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->CommonRepo = $CommonRepo;
        $this->BookingRepo = $BookingRepo;
        $this->PropertyType = $PropertyType;
        //$this->MyBookingsRepository = $MyBookingsRepository;
        $this->MyDashboardRepository = $MyDashboardRepository;
    }
 
    public function index(Request $request)
    {
        $vendors = $this->BookingRepo->getVendors();
        $propertyTypes = $this->PropertyType->getAllRecord();
        if(request()->ajax()) 
        {
            return $this->BookingRepo->getAjaxData($request);
        }
        return view('booking::index',compact('vendors','propertyTypes'))->withModel(strtolower($this->model));
    }

    public function show($slug)
    {
        $booking = $this->BookingRepo->getRecordBySlug($slug);
        if (empty($booking)) {
            Session::flash('error', 'Booking not found.');
            return redirect()->route('booking.index');
        }
        $jsonData = json_decode($booking->property_booking_data);
        return view('booking::backend.show', compact('booking', 'jsonData'));
    }

    /* public function myBookings(Request $request)
    {
        $records = $this->MyBookingsRepository->getAllMyBookingsRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('booking::frontend.my_bookings.get_ajax_tabledata',compact('records'))->withModel('booking')->render())));
        }
        return view('booking::frontend.my_bookings.index', compact('records','propertyTypes'));
    } */

    /**
     * upload user profile picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(ProfileMediaRequest $request)
    {
        try {
            $response = $this->CommonRepo->saveProfilePictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function notifications(Request $request)
    {
        $notifications = $this->MyDashboardRepository->getAllNotifications($request);
        if ($request->ajax()) {
            return Response::json(array('apppendid' => "result_all_notification", 'body' => json_encode(View::make("propertyownerdashboard::frontend.notification.ajax_all_notifics", compact('notifications'))->render())));
        }
        return view('propertyownerdashboard::frontend.notification.index', compact('notifications'));
    }

    public function getCompleteProfile(Request $request)
    {
        return view('propertyownerdashboard::frontend.auth.complete_profile');
    }

    public function uploadSelfyAndLogo(ProfileMediaRequest $request)
    {
        try {
            $response = $this->CommonRepo->uploadSelfyAndLogoMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function uploadUserImagePdf(UploadImagePdfRequest $request)
    {
        try {
            $response = $this->CommonRepo->uploadUserDcuments($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function submitCompleteProfile(SubmitCompleteProfileRequest $request)
    {
        try {
            $response = $this->MyDashboardRepository->submitUserProfileVerificationData($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash($response['type'], trans($response['message']));
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function deactivateAccount(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->deactivateAccount($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash($response['type'], $response['message']);
            return route('vendor.login');
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->deleteAccount($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', 'Account deactivate successfully');
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function myproperty(Request $request)
    {
        $records = $this->MyDashboardRepository->getAllMyPropertyRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();

        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('propertyownerdashboard::frontend.myProperty.ajax_all_myproperty', compact('records'))->withModel('propertyownerdashboard')->render())));
        }
        return view('propertyownerdashboard::frontend.myProperty.index', compact('records', 'propertyTypes'));
    }

    public function status(Request $request,$slug){
            $response = $this->BookingRepo->changeStatus($request,$slug);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);  
            return back(); 
        
    }
}
