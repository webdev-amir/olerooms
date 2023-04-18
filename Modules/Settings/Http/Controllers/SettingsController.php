<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Settings\Http\Requests\UploadSettingAgreementMediaRequest;
use Illuminate\Support\Facades\Session;
use Modules\Settings\Http\Requests\UpdateSiteSettingsRequest;
use Modules\Settings\Repositories\SettingsRepositoryInterface as SettingsRepo;

class SettingsController extends Controller
{
    protected $SettingsRepo;
    
    public function __construct(SettingsRepo $SettingsRepo)
    {
        $this->middleware(['ability', 'auth']);
        $this->SettingsRepo = $SettingsRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return $this->SettingsRepo->getAjaxData($request);
        }
        return view('settings::index')->withModel('settings');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('settings::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $response = $this->SettingsRepo->store($request);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('settings.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $slug)
    {
        $data =  $this->SettingsRepo->getRecordBySlug($slug);
        if ($data) {
            return view('settings::edit', compact('data'))->withModel('settings');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('settings.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateSiteSettingsRequest $request, $id)
    {
        $data =  $this->SettingsRepo->getRecord($id);
        if ($data) {
            $response = $this->SettingsRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('settings.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('settings.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request, $id)
    {
        try {
            $data =  $this->SettingsRepo->getRecord($id);
            if ($data) {
                $this->SettingsRepo->destroy($data->id);
                $type = 'success';
                $message = trans('flash.success.settings_deleted_successfully');
                if ($request->ajax()) {
                    return response()->json(['status_code' => 200, 'type' => $type, 'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('settings.index');
            }
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('settings.index');
        } catch (QueryException $e) {
            if ($request->ajax()) {
                return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('settings.index');
        }
    }

    public function saveMedia(UploadSettingAgreementMediaRequest $request)
    {
        try {
            $response = $this->SettingsRepo->saveBannerImageMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
