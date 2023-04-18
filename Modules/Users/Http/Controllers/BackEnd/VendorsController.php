<?php

namespace Modules\Users\Http\Controllers\BackEnd;

use Session, View, Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Repositories\VendorsRepositoryInterface as VendorsRepo;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use Modules\Users\Http\Requests\ChangePasswordRequest;
use Modules\Users\Http\Requests\UpdateVendorRequest;
use Modules\Users\Http\Requests\CreateVendorRequest;
use Modules\Booking\Entities\Booking;
use Modules\ScheduleVisit\Entities\ScheduleVisit;

class VendorsController extends Controller
{
    protected $role = 'vendor';
    public function __construct(
        VendorsRepo $VendorsRepo,
        Booking $Booking,
        ScheduleVisit $ScheduleVisit
    ) {
        $this->middleware(['ability', 'auth']);
        $this->VendorsRepo = $VendorsRepo;
        $this->Booking = $Booking;
        $this->ScheduleVisit = $ScheduleVisit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->VendorsRepo->getAll($request, $this->role);
        return view('users::admin.vendor.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('users::admin.vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateVendorRequest $request)
    {
        $response = $this->VendorsRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('vendor.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug)
    {
        $user =  $this->VendorsRepo->getRecordBySlug($slug);
        if ($user) {
            return view('users::admin.vendor.show', compact('user'));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('vendor.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $user =  $this->VendorsRepo->getRecordBySlug($slug);
        if ($user) {
            return view('users::admin.vendor.edit', compact('user'));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('vendor.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateVendorRequest $request, $id)
    {
        $data =  $this->VendorsRepo->getRecord($id);
        if ($data) {
            $response = $this->VendorsRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('vendor.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('vendor.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $data =  $this->VendorsRepo->getRecord($id);
            if ($data) {
                $bookingCount = $this->Booking->where('vendor_id', $id)->whereIn('status', ['in-progress', 'pending', 'request', 'confirmed'])->count();
                $scheduleVisitCount = $this->ScheduleVisit->whereHas('scheduleVisitProperty.property', function ($query) use ($id) {
                    $query->where("user_id", $id);
                })->where('status', 'request')->count();

                if ($bookingCount > 0 || $scheduleVisitCount > 0) {
                    $status = 'error';
                    $message = "You can't delete this account. Property owner have booking in progress.";
                } else {
                    $this->VendorsRepo->destroy($id);
                    $status = 'success';
                    $message = trans('flash.success.user_deleted_successfully');
                }
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'status' => $status, 'message' => $message]);
                }
                Session::flash($status, $message);
                return redirect()->route('vendor.index');
            }

            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'status' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('vendor.index');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'status' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('vendor.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $slug)
    {
        $response = $this->VendorsRepo->changeStatus($request, $slug);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }

    /**
     * Update the passowrd for requested user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    protected function storeChangeUserPassword(ChangePasswordRequest $request)
    {
        try {
            $response = $this->VendorsRepo->updateUserPassword($request);
            if ($request->ajax()) {
                return response()->json($response);
            }
            $request->session()->flash('success', trans('flash.success.password_has_been_changed'));
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
            $response = $this->VendorsRepo->saveProfilePictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function documentVerificationStatusView($id)
    {
        $data =  $this->VendorsRepo->getRecord($id);
        if ($data) {
            return Response::json(array('body' => json_encode(View::make('users::admin.vendor.approve_modal', compact(
                'data'
            ))->withModel('users')->render())));
        }
        $response['type'] = 'error';
        $response['message'] = trans('flash.error.record_not_available_now');
        return response()->json($response);
    }

    public function documentVerificationStatusUpdate(Request $request, $id)
    {
        $response = $this->VendorsRepo->changeDocumentVerificationStatus($request, $id);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }
}
