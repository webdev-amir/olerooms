<?php

namespace Modules\Agent\Http\Controllers\Frontend;

use Session, View, Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use App\Http\Requests\UploadImagePdfRequest;
use Modules\MyDashboard\Http\Requests\UpdatePasswordRequest;
use Modules\Agent\Http\Requests\UpdateUserProfileDetailsRequest;
use Modules\Users\Http\Requests\UpdateUserBankDetailsRequest;
use App\Repositories\Common\CommonRepository as CommonRepo;
use Modules\Agent\Repositories\Frontend\MyDashboard\MyDashboardRepository as MyDashboardRepository;

class MyDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $CommonRepo;
    public $MyDashboardRepository;

    public function __construct(CommonRepo $CommonRepo, MyDashboardRepository $MyDashboardRepository)
    {

        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->CommonRepo = $CommonRepo;
        $this->MyDashboardRepository = $MyDashboardRepository;
    }

    public function mydashboard(Request $request)
    {
        $records = $this->MyDashboardRepository->getDashboardRecord($request);
        $bookingRequests = $this->MyDashboardRepository->getBookingRequests($request);
        if ($request->ajax()) {
            $bookingRequests = $this->MyDashboardRepository->getBookingRequests($request);
            return Response::json(array('body' => json_encode(View::make('agent::frontend.my_bookings.ajax_booking_requests', compact('bookingRequests'))->withModel('booking')->render())));
        }
        return view('agent::frontend.dashboard', compact('records', 'bookingRequests'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myprofile()
    {
        return view('agent::frontend.my_profile');
    }

    public function myBookings(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $records = $this->MyDashboardRepository->getAllMyBookingsRecord($request);
            $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
            return Response::json(array('body' => json_encode(View::make('agent::frontend.my_bookings.ajax_all_mybooking', compact('type', 'records'))->withModel('booking')->render())));
        }

        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $records = $this->MyDashboardRepository->getAllMyBookingsRecord($request);
        $propertyTypes = $this->MyDashboardRepository->getAllPropertyTypes();
        return view('agent::frontend.my_bookings.index', compact('type', 'records', 'propertyTypes'));
    }

    public function myBookingsDetails(Request $request, $slug)
    {
        $data = $this->MyDashboardRepository->MyBookingsDetails($request, $slug);
        if ($data) {
            $jsonData = json_decode($data->property_booking_data);
            return view('agent::frontend.my_bookings.show', compact('data', 'jsonData'))->withModel('agent');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('vendor.dashboard.mybooking');
    }

    public function notifications(Request $request)
    {
        $notifications = $this->MyDashboardRepository->getAllNotifications($request);
        if ($request->ajax()) {
            return Response::json(array('apppendid' => "result_all_notification", 'body' => json_encode(View::make("agent::frontend.notification.ajax_all_notifics", compact('notifications'))->render())));
        }
        return view('agent::frontend.notification.index', compact('notifications'));
    }

    public function changePassword()
    {
        return view('agent::change-password');
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


    public function uploadBankImages(ProfileMediaRequest $request)
    {
        try {
            $response = $this->CommonRepo->uploadBankImages($request);
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

    public function myEarnings(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $response = $this->MyDashboardRepository->getMyPaymentsEarningHistory($request);
            $records = $response['records'];
            return Response::json(array('totalEarnings' => $response['totalEarnings'], 'totalBookings' => $response['totalBookings'], 'body' => json_encode(View::make('agent::frontend.myearnings.ajax_myearnings', compact('type', 'records'))->withModel('booking')->render())));
        }
        $type = ($request->get('type')) ? $request->get('type') : 'all';
        $propertyList = $this->MyDashboardRepository->getAllPropertiesForMyEarningsFilter();
        $response = $this->MyDashboardRepository->getMyPaymentsEarningHistory($request);
        $records = $response['records'];
        return view('agent::frontend.myearnings.index', compact('type', 'records', 'propertyList'));
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
}
