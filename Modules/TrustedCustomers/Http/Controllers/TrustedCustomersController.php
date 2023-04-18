<?php

namespace Modules\TrustedCustomers\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\TrustedCustomers\Entities\TrustedCustomers;
use Modules\TrustedCustomers\Http\Requests\CreateTrustedCustomersRequest;
use Modules\TrustedCustomers\Http\Requests\UpdateTrustedCustomersRequest;
use Modules\TrustedCustomers\Http\Requests\TrustedCustomersMediaRequest;
use Modules\TrustedCustomers\Repositories\TrustedCustomersRepositoryInterface as TrustedCustomersRepo;

class TrustedCustomersController extends Controller
{
    protected $model = 'TrustedCustomers';

    public function __construct(TrustedCustomersRepo $TrustedCustomersRepo)
    {
        $this->middleware(['auth', 'ability']);
        $this->TrustedCustomersRepo = $TrustedCustomersRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     **/
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return $this->TrustedCustomersRepo->getAjaxData($request);
        }
        return view('trustedcustomers::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $rating = 1;
        return view('trustedcustomers::create', compact('rating'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateTrustedCustomersRequest $request)
    {
        $response = $this->TrustedCustomersRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('trustedcustomers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->TrustedCustomersRepo->getRecordBySlug($slug);
        if ($data) {
            $rating = $data->rating;
            return view('trustedcustomers::edit', compact('data', 'rating'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('trustedcustomers.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateTrustedCustomersRequest $request, $id)
    {
        $data =  $this->TrustedCustomersRepo->getRecord($id);
        if ($data) {
            $response = $this->TrustedCustomersRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('trustedcustomers.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('trustedcustomers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $slug)
    {
        try {
            $data =  $this->TrustedCustomersRepo->getRecordBySlug($slug);
            if ($data) {
                $this->TrustedCustomersRepo->destroy($data->id);
                $type = 'success';
                $message = trans('flash.success.trustedcustomers_deleted_successfully');
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'type' => $type, 'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('trustedcustomers.index');
            }
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('trustedcustomers.index');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('trustedcustomers.index');
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
        $response = $this->TrustedCustomersRepo->changeStatus($request, $slug);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return back();
    }

    /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(TrustedCustomersMediaRequest $request)
    {
        try {
            $response = $this->TrustedCustomersRepo->saveTrustedCustomersPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
