<?php

namespace Modules\Teams\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Teams\Http\Requests\TeamsMediaRequest;
use Modules\Teams\Http\Requests\CreateTeamRequest;
use Modules\Teams\Http\Requests\UpdateTeamRequest;
use Modules\Teams\Repositories\TeamsRepositoryInterface as TeamsRepo;
use Illuminate\Support\Facades\Session;

class TeamsController extends Controller
{
    protected $model = 'Teams';

    public function __construct(TeamsRepo $TeamsRepo)
    {
        $this->middleware(['ability', 'auth'], ['except' => ['store', 'create']]);
        $this->TeamsRepo = $TeamsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return $this->TeamsRepo->getAjaxData($request);
        }
        return view('teams::index')->withModel(strtolower($this->model));
    }

    public function create()
    {

        return view('teams::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateTeamRequest $request)
    {
        $response = $this->TeamsRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('teams.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->TeamsRepo->getRecordBySlug($slug);
        if ($data) {
            return view('teams::edit', compact('data'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('teams.index');
    }

    /** 
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateTeamRequest $request, $id)
    {
        $data =  $this->TeamsRepo->getRecord($id);
        if ($data) {
            $response = $this->TeamsRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('teams.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('teams.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $slug)
    {
        try {
            $data =  $this->TeamsRepo->getRecordBySlug($slug);
            if ($data) {
                $this->TeamsRepo->destroy($data->id);
                $type = 'success';
                $message = trans('flash.success.teams_deleted_successfully');
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'type' => $type, 'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('teams.index');
            }
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('teams.index');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('teams.index');
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
        $response = $this->TeamsRepo->changeStatus($request, $slug);
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
    public function saveMedia(TeamsMediaRequest $request)
    {
        try {
            $response = $this->TeamsRepo->saveTeamsPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
