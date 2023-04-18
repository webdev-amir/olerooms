<?php

namespace Modules\Company\Http\Controllers\Frontend;

use Session, View, Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use Modules\MyDashboard\Http\Requests\UpdatePasswordRequest;
use Modules\Company\Http\Requests\UpdateUserProfileDetailsRequest;
use Modules\Users\Http\Requests\UpdateUserBankDetailsRequest;
use App\Repositories\Common\CommonRepository as CommonRepo;
use App\Repositories\Frontend\FrontendRepository as FrontendRepository;
use Modules\Company\Repositories\Frontend\MyDashboard\MyDashboardRepository as MyDashboardRepository;


class MyDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $MyDashboardRepository;
    protected $CommonRepo;
    protected $FrontendRepository;

    public function __construct(CommonRepo $CommonRepo, FrontendRepository $FrontendRepository, MyDashboardRepository $MyDashboardRepository)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->CommonRepo = $CommonRepo;
        $this->FrontendRepository = $FrontendRepository;
        $this->MyDashboardRepository = $MyDashboardRepository;
    }

    public function company(Request $request)
    {
        $records = $this->MyDashboardRepository->getDashboardRecord($request);
        $bookingRequests = $this->MyDashboardRepository->getBookingRequests($request);

        return view('company::frontend.dashboard', compact('records', 'bookingRequests'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myprofile()
    {
        $stateLists = $this->FrontendRepository->getstateListsForOptions();
        return view('company::frontend.my_profile', compact('stateLists'));
    }

    public function myBookings(Request $request)
    {
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyBookingsRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('company::frontend.my_bookings.ajax_all_mybooking', compact('type', 'records'))->withModel('booking')->render())));
        }
        return view('company::frontend.my_bookings.index', compact('type', 'records', 'propertyTypes'));
    }

    public function myBookingsDetails($slug)
    {
        $data = $this->MyDashboardRepository->MyBookingsDetails($slug);
        if ($data) {
            $jsonData = json_decode($data->property_booking_data);
            return view('company::frontend.my_bookings.show', compact('data', 'jsonData'))->withModel('company');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('company.dashboard.mybooking');
    }

    public function myCodeBookings(Request $request)
    {
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyCodeBookingsRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('company::frontend.code_bookings.ajax_all_mybooking', compact('type', 'records'))->withModel('booking')->render())));
        }
        return view('company::frontend.code_bookings.index', compact('type', 'records', 'propertyTypes'));
    }



    public function myCodeBookingsDetails($slug)
    {
        $data = $this->MyDashboardRepository->MyBookingsDetails($slug);
        if ($data) {
            $jsonData = json_decode($data->property_booking_data);
            return view('company::frontend.code_bookings.show', compact('data', 'jsonData'))->withModel('company');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('company.dashboard.mybooking');
    }


    public function notifications(Request $request)
    {
        $notifications = $this->MyDashboardRepository->getAllNotifications($request);
        if ($request->ajax()) {
            return Response::json(array('apppendid' => "result_all_notification", 'body' => json_encode(View::make("company::frontend.notification.ajax_all_notifics", compact('notifications'))->render())));
        }
        return view('company::frontend.notification.index', compact('notifications'));
    }

    public function changePassword()
    {
        return view('company::change-password');
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
            $response = $this->MyDashboardRepository->updateUserProfileDetails($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', trans('flash.success.password_updated_successfully'));
            return back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }

    public function updateBankDetails(UpdateUserBankDetailsRequest $request)
    {
        try {
            $response = $this->MyDashboardRepository->updateBankDetails($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', 'Bank details upadted successfully');
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

    public function deactivateAccount(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->deactivateAccount($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash($response['type'], $response['message']);
            return route('company.login');
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

    public function myvisit(Request $request)
    {
        if ($request->ajax()) {
            if ($request->get('type')) {
                $type = $request->get('type');
                $records = $this->MyDashboardRepository->getAllMyVisitRecord($request);
                return Response::json(array('body' => json_encode(View::make('company::frontend.myvisit.ajax_all_myvisit', compact('type', 'records'))->withModel('company')->render())));
            }
        }
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyVisitRecord($request);
        return view('company::frontend.myvisit.index', compact('type', 'records'));
    }

    public function myvisitDetails(Request $request, $visit_slug)
    {
        $data = $this->MyDashboardRepository->myvisitDetailsRecord($visit_slug);
        if ($data) {
            return view('company::frontend.myvisit.myvisit_details', compact('data'))->withModel('company');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('company.dashboard.myvisit');
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

    public function myEarnings(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $response = $this->MyDashboardRepository->getMyPaymentsEarningHistory($request);
            $records = $response['records'];
            return Response::json(array('totalEarnings' => $response['totalEarnings'], 'totalBookings' => $response['totalBookings'], 'body' => json_encode(View::make('company::frontend.myearnings.ajax_myearnings', compact('type', 'records'))->withModel('booking')->render())));
        }
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $propertyList = $this->MyDashboardRepository->getAllPropertiesForMyEarningsFilter();
        $response = $this->MyDashboardRepository->getMyPaymentsEarningHistory($request);
        $records = $response['records'];
        return view('company::frontend.myearnings.index', compact('type', 'records', 'propertyList'));
    }

    public function sendRedeemCreditRequest(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->sendRedeemCreditRequest($request);
            if ($request->ajax()) {
                return response()->json($response);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json(['type' => 'error', "status" => false, "message" => $e->getMessage()]);
        }
    }


    public function uploadBankImages(ProfileMediaRequest $request)
    {
        try {
            $response = $this->CommonRepo->uploadBankImages($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
