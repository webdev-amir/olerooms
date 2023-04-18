<?php

namespace Modules\Users\Http\Controllers\BackEnd;

use Session,
    View,
    Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Repositories\UsersRepositoryInterface as UsersRepo;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use Modules\Users\Http\Requests\ChangePasswordRequest;
use Modules\Users\Http\Requests\CreateUserRequest;
use Modules\Users\Http\Requests\UpdateUserRequest;

class UsersController extends Controller {

    protected $role = 'customer';

    public function __construct(UsersRepo $UsersRepo, CommonRepo $CommonRepo) {
        $this->middleware(['ability', 'auth']);
        $this->UsersRepo = $UsersRepo;
        $this->CommonRepo = $CommonRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        $users = $this->UsersRepo->getAll($request, $this->role);
        return view('users::admin.user.index', compact('users'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug) {
        $user = $this->UsersRepo->getRecordBySlug($slug);
        if ($user) {
            return view('users::admin.user.show', compact('user'));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug) {
        $user = $this->UsersRepo->getRecordBySlug($slug);
        if ($user) {
            $cityPluck = $this->CommonRepo->getCityPluck();
            return view('users::admin.user.edit', compact('user', 'cityPluck'));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateUserRequest $request, $id) {
        $data = $this->UsersRepo->getRecord($id);
        if ($data) {
            $response = $this->UsersRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('users.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('users.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        $cityPluck = $this->CommonRepo->getCityPluck();
        return view('users::admin.user.create', compact('cityPluck'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateUserRequest $request) {
        $response = $this->UsersRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        if ($response['type'] == 'error') {
            return back();
        } else {
            return redirect()->route('users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id) {
        try {
            $data = $this->UsersRepo->getRecord($id);
            if ($data) {
                $this->UsersRepo->destroy($id);
                $status = 'success';
                $message = trans('flash.success.user_deleted_successfully');
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
    public function status(Request $request, $slug) {
        $response = $this->UsersRepo->changeStatus($request, $slug);
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
    protected function storeChangeUserPassword(ChangePasswordRequest $request) {
        try {
            $response = $this->UsersRepo->updateUserPassword($request);
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
    public function saveMedia(ProfileMediaRequest $request) {
        try {
            $response = $this->UsersRepo->saveProfilePictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

}
