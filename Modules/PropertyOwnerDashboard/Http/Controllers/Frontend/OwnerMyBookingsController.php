<?php

namespace Modules\PropertyOwnerDashboard\Http\Controllers\Frontend;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use App\Http\Requests\UploadImagePdfRequest;
use Modules\MyDashboard\Http\Requests\UpdatePasswordRequest;
use Modules\MyDashboard\Http\Requests\CheckPasswordRequest;
use Modules\PropertyOwnerDashboard\Http\Requests\UpdateUserProfileDetailsRequest;
use Modules\PropertyOwnerDashboard\Http\Requests\SubmitCompleteProfileRequest;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
// use Modules\PropertyOwnerDashboard\Repositories\Frontend\MyBookings\MyBookingsRepositoryInterface as MyBookingsRepository;
use Modules\PropertyOwnerDashboard\Http\Requests\UploadSelfieMediaRequest;
use Modules\PropertyOwnerDashboard\Http\Requests\UploadAgreementMediaRequest;


class OwnerMyBookingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommonRepo $CommonRepo)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->CommonRepo = $CommonRepo;
        // $this->MyBookingsRepository = $MyBookingsRepository;
    }

    public function mydashboard(Request $request)
    {
        $records = $this->MyDashboardRepository->getDashboardRecord();

        return view('propertyownerdashboard::frontend.dashboard', compact('records'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function myprofile()
    {
        return view('propertyownerdashboard::frontend.my_profile');
    }

    public function myBookings()
    {
        return view('propertyownerdashboard::frontend.my_bookings.index');
    }

    public function changePassword()
    {
        return view('propertyownerdashboard::change-password');
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

    public function getAllOffers(Request $request)
    {

        if ($request->ajax()) {
            $coupon = $this->MyDashboardRepository->getAllCoupons($request);
            return response()->json($coupon);
        }
    }

    public function mypropertyStatus(Request $request)
    {
        $response = $this->MyDashboardRepository->mypropertyChangeStatus($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }

    public function mypropertyUploadSelfie(UploadSelfieMediaRequest $request)
    {
        try {
            $response = $this->MyDashboardRepository->mypropertyUploadSelfieMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function mypropertyUploadAgreement(UploadAgreementMediaRequest $request)
    {
        try {
            $response = $this->MyDashboardRepository->mypropertyUploadAgreementMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function mypropertyUploadSelfieOrAgreementSave(Request $request)
    {
        $data = $this->MyDashboardRepository->getRecord($request->get('property_id'));
        if ($data) {
            $response = $this->MyDashboardRepository->updateUploadSelfieOrAgreement($request);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('vendor.myproperty');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('vendor.myproperty');
    }

    public function downloadAgreement($filename = '')
    {
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "/app/public/coupon/" . $filename;
        $headers = array(
            'Content-Type: csv',
            'Content-Disposition: attachment; filename=' . $filename,
        );
        if (file_exists($file_path)) {
            // Send Download
            return \Response::download($file_path, $filename, $headers);
        } else {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function deleteMyProperty(Request $request)
    {
        try {
            $data =  $this->MyDashboardRepository->getRecord($request->get('property_id'));
            if ($data) {
                $id = $data->id;
                $this->MyDashboardRepository->destroy($id);
                $status = 'success';
                $message = 'My Property deleted successfully';
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'status' => $status, 'message' => $message]);
                }
                Session::flash($status, $message);
                return redirect()->route('vendor.myproperty');
            }

            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'status' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('vendor.myproperty');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'status' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('vendor.myproperty');
        }
    }

    public function offerApply(Request $request)
    {
        try {
            $response = $this->MyDashboardRepository->storePropertyOffer($request);
            if ($request->wantsJson()) {
                return response()->json($response);
            }
            $request->session()->flash('success', 'Account deactivate successfully');
            return back();
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'status' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('vendor.myproperty');
        }
    }
}
