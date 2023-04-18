<?php

namespace Modules\MyDashboard\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use Modules\MyDashboard\Http\Requests\UpdatePasswordRequest;
use Modules\MyDashboard\Http\Requests\CheckPasswordRequest;
use Modules\MyDashboard\Http\Requests\UpdateUserProfileDetailsRequest;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\MyDashboard\Repositories\MyDashboardRepositoryInterface as MyDashboardRepository;
use App\Models\User;

class MyDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonRepo $CommonRepo, MyDashboardRepository $MyDashboardRepository)
    {
        // $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->middleware(['auth', 'prevent-back-history']);
        $this->CommonRepo = $CommonRepo;
        $this->MyDashboardRepository = $MyDashboardRepository;
    }

    public function mydashboard(Request $request)
    {
        return view('mydashboard::dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myprofile()
    {
        $cityPluck = $this->CommonRepo->getCityPluck();
        return view('mydashboard::my_profile', compact('cityPluck'));
    }

    public function mybooking(Request $request)
    {
        if ($request->ajax()) {
            $type  = ($request->get('type')) ? $request->get('type') : 'all';
            $records = $this->MyDashboardRepository->getAllMyBookingsRecord($request);
            $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
            return Response::json(array('body' => json_encode(View::make('mydashboard::manageBooking.ajax_all_mybooking', compact('type', 'records'))->withModel('booking')->render())));
        }

        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyBookingsRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
        return view('mydashboard::manageBooking.index', compact('type', 'records', 'propertyTypes'));
    }

    public function myBookingsDetails($slug)
    {
        $data = $this->MyDashboardRepository->MyBookingsDetails($slug);
        if ($data) {
            $jsonData = json_decode($data->property_booking_data);
            return view('mydashboard::manageBooking.show', compact('data', 'jsonData'))->withModel('mydashboard');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('customer.dashboard.mybooking');
    }

    public function changePassword()
    {
        return view('mydashboard::change-password');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $response = $this->CommonRepo->updateUserPassword($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', trans('flash.success.password_updated_successfully'));
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function updateProfile(UpdateUserProfileDetailsRequest $request)
    {
        try {
            if ($request->mobile_otp) {
                $response = $this->MyDashboardRepository->updateUserProfileDetailsWithOTP($request);
            } elseif ($request->phone != auth()->user()->phone) {
                $response = $this->MyDashboardRepository->sendOtpResponse($request);
            } else {
                $response = $this->MyDashboardRepository->updateUserProfileDetails($request);
            }
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', trans('flash.success.profile_updated_successfully'));
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

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

    public function managePayment(Request $request)
    {
        $payments = $this->MyDashboardRepository->getMyPaymentsHistory($request);
        if ($request->ajax()) {
            return Response::json(array('apppendid' => "result_all_payments", 'body' => json_encode(View::make("mydashboard::managePayment.ajax_all_payments", compact('payments'))->render())));
        }
        return view('mydashboard::managePayment.index', compact('payments'));
    }

    public function notifications(Request $request)
    {
        $notifications = $this->MyDashboardRepository->getAllNotifications($request);
        if ($request->ajax()) {
            return Response::json(array('apppendid' => "result_all_notification", 'body' => json_encode(View::make("mydashboard::notification.ajax_all_notifics", compact('notifications'))->render())));
        }
        return view('mydashboard::notification.index', compact('notifications'));
    }

    public function deleteUserAccount(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->deleteAccount($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', 'Account deleted successfully');
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function myvisit(Request $request)
    {
        if ($request->ajax()) {
            if ($request->get('type')) {
                $type = $request->get('type');
                $records = $this->MyDashboardRepository->getAllMyVisitRecord($request);
                return Response::json(array('body' => json_encode(View::make('mydashboard::myvisit.ajax_all_myvisit', compact('type', 'records'))->withModel('mydashboard')->render())));
            }
        }
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyVisitRecord($request);
        return view('mydashboard::myvisit.index', compact('type', 'records'));
    }

    public function myvisitDetails(Request $request, $visit_slug)
    {
        $data = $this->MyDashboardRepository->myvisitDetailsRecord($visit_slug);
        if ($data) {
            return view('mydashboard::myvisit.myvisit_details', compact('data'))->withModel('mydashboard');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('customer.dashboard.myvisit');
    }

    public function cancellBookingRequest(Request $request)
    {
        $response = $this->MyDashboardRepository->cancellBookingRequest($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }

    public function cancellVisitRequest(Request $request)
    {
        $response = $this->MyDashboardRepository->cancellVisitRequest($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }
}
