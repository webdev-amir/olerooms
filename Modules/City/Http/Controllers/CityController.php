<?php

namespace Modules\City\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\City\Entities\State;
use Modules\City\Http\Requests\UpdateAreaRequest;
use Modules\City\Http\Requests\CreateCityRequest;
use Modules\City\Http\Requests\BannerImageMediaRequest;
use Modules\City\Repositories\CityRepositoryInterface as CityRepo;
use DB, Response, View;
use Session;

class CityController extends Controller
{
    public function __construct(CityRepo $CityRepo)
    {
        $this->middleware(['ability', 'auth']);
        $this->CityRepo = $CityRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function cityStateList(Request $request, $id)
    {
        $records = $this->CityRepo->getAjaxDataRecords($request, $id);
        $state = State::where('states.id', $id)->first();
        if ($request->ajax()) {
            return Response::json(array('body' => json_encode(View::make('city::includes.ajax_city_list', compact('records', 'state'))->withModel('city')->render())));
        }
        return view('city::city.index', compact('records', 'state'))->withModel('city');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request, $stateId)
    {
        return view('city::city.create', compact('stateId'))->withModel('city');
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */

    public function store(CreateCityRequest $request, $stateId)
    {
        $response = $this->CityRepo->store($request, $stateId);
        if ($request->ajax()) {
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);
        return redirect()->route('state.city', [$stateId]);
    }


    public function editCity(Request $request, $stateId, $id)
    {
        $data =  $this->CityRepo->getRecord($id);
        $state = State::where('states.id', $stateId)->first();
        if ($data && $state) {
            return view('city::city.edit', compact('data', 'state'))->withModel('city');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('city.edit');
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateCity(UpdateAreaRequest $request, $stateId, $id)
    {
        $data =  $this->CityRepo->getRecord($id);
        $state = State::where('states.id', $stateId)->first();
        if ($data && $state) {
            $response = $this->CityRepo->update($request, $id);
            if ($request->ajax()) {
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']);
            return redirect()->route('state.city', $state->id);
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('city.index');
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $data =  $this->CityRepo->getRecord($id);
            if ($data) {
                $this->CityRepo->destroy($id);
                Session::flash('success', 'City deleted Successfully');
                return redirect()->route('city.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('city.index');
        } catch (QueryException $e) {
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('city.index');
        }
    }

    public function cityStatus(Request $request, $id)
    {
        try {
            $data = $this->CityRepo->getRecord($id);
            if ($data) {
                $response = $this->CityRepo->changeCityStatus($request, $id);
                if ($request->ajax()) {
                    return response()->json($response);
                }
                Session::flash('success', 'City status is updated successfully');
                return redirect()->route('city.index');
            }
        } catch (QueryException $e) {
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('city.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(BannerImageMediaRequest $request)
    {
        try {
            $response = $this->CityRepo->saveBannerImageMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
