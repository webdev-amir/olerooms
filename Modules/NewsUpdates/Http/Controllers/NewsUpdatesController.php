<?php

namespace Modules\NewsUpdates\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\NewsUpdates\Http\Requests\NewsUpdatesMediaRequest;
use Modules\NewsUpdates\Http\Requests\CreateNewsUpdatesRequest;
use Modules\NewsUpdates\Http\Requests\UpdateNewsUpdatesRequest;
use Modules\NewsUpdates\Repositories\NewsUpdatesRepositoryInterface as NewsUpdatesRepo;
use Illuminate\Support\Facades\Session;

class NewsUpdatesController extends Controller
{
    protected $model = 'NewsUpdates';

    public function __construct(NewsUpdatesRepo $NewsUpdatesRepo)
    {
        $this->middleware(['ability', 'auth'], ['except' => ['store', 'create']]);
        $this->NewsUpdatesRepo = $NewsUpdatesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return $this->NewsUpdatesRepo->getAjaxData($request);
        }
        return view('newsupdates::index')->withModel(strtolower($this->model));
    }

    public function create()
    {
        return view('newsupdates::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateNewsUpdatesRequest $request)
    {
        $response = $this->NewsUpdatesRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('newsupdates.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->NewsUpdatesRepo->getRecordBySlug($slug);
        if ($data) {
            return view('newsupdates::edit', compact('data'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('newsupdates.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateNewsUpdatesRequest $request, $id)
    {
        $data =  $this->NewsUpdatesRepo->getRecord($id);
        if ($data) {
            $response = $this->NewsUpdatesRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('newsupdates.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('newsupdates.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $slug)
    {
        try {
            $data =  $this->NewsUpdatesRepo->getRecordBySlug($slug);
            if ($data) {
                $this->NewsUpdatesRepo->destroy($data->id);
                $type = 'success';
                $message = trans('flash.success.newsupdates_deleted_successfully');
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'type' => $type, 'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('newsupdates.index');
            }
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('newsupdates.index');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('newsupdates.index');
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
        $response = $this->NewsUpdatesRepo->changeStatus($request, $slug);
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
    public function saveMedia(NewsUpdatesMediaRequest $request)
    {
        try {
            $response = $this->NewsUpdatesRepo->saveNewsUpdatesPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
