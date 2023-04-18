<?php

namespace Modules\City\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\City\Entities\State;
use Modules\City\Entities\City;
use Modules\City\Http\Requests\CreateAreaRequest;
use Modules\City\Http\Requests\UpdateAreaRequest;
use Modules\City\Repositories\AreaRepositoryInterface as AreaRepo;
use Session,View, Response;

class AreaController extends Controller
{
    public function __construct(AreaRepo $AreaRepo)
    {
        $this->middleware(['ability','auth']);
        $this->AreaRepo = $AreaRepo;
    }
  
    public function cityAreaList(Request $request, $stateId,$id)
    {
        $state = State::where('states.id',$stateId)->first();
        $city = $this->AreaRepo->getCity($id);
        $records = $this->AreaRepo->getAjaxDataRecords($request,$id);
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('city::area.ajax_area_list',compact('records','city','state'))->withModel('city')->render())));
        }
		return view('city::area.index',compact('records','state','city'))->withModel('area');
    }
    public function create(Request $request, $stateId, $id)
    {
        $state = State::where('states.id',$stateId)->first();
        $city = City::where('cities.id',$id)->first();
        return view('city::area.create',compact('state','city'))->withModel('area');

    }

    public function store(CreateAreaRequest $request,$stateId, $id)
    {
        $state = State::where('states.id',$stateId)->first();
        $city = City::where('cities.id',$id)->first();
        $response = $this->AreaRepo->store($request,$id);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('state.city.areas',[$state->id, $city->id]);
    }

    public function edit($stateId, $id, $slug)
    {
        $state = State::where('states.id',$stateId)->first();
        $city = $this->AreaRepo->getCity($id);
        $data =  $this->AreaRepo->getRecordBySlug($slug,$id);
        if($data){
          $id = $data->city_id;
          return view('city::area.edit',compact('data','id','city','state'))->withModel('area');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('state.city.area',$state->id, $city->id);
    }

    public function update(Request $request,$stateId, $id, $areaId)
    {
        $state = State::where('states.id',$stateId)->first();
        $city = $this->AreaRepo->getCity($id);
        $data =  $this->AreaRepo->getRecord($areaId);
        if($data){
            $response = $this->AreaRepo->update($request,$id,$areaId);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('state.city.areas',[$state->id,$city->id]);
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('state.area.edit',$state->id,$city->id,$data->slug);
    }

    public function destroy(Request $request,$stateId,$id,$slug)
    {
        try{
            $state = State::where('states.id',$stateId)->first();
            $city = $this->AreaRepo->getCity($id);
            $data =  $this->AreaRepo->getRecordBySlug($slug,$id);
            if($data){
                $this->AreaRepo->destroy($data->id);
                $type = 'success'; $message =' Area deleted successfully';
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('city.areas',$id);
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('city.areas',$id);
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('city.areas',$id);
        }
    }

    public function areaStatus(Request $request,$stateId, $id, $slug)
    {
        try {
            $state = State::where('states.id',$stateId)->first();
            $city = $this->AreaRepo->getCity($id);
            $data = $this->AreaRepo->getRecordBySlug($slug,$id);
            if($data){
                $response = $this->AreaRepo->changeAreaStatus($request, $id,$slug);
                if($request->ajax()){
                    return response()->json($response);
                }
                Session::flash('success','Area status is updated successfully');
                return redirect()->route('state.city.areas',[$state->id,$city->id]);
            }
        }catch (QueryException $e){
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('state.city.areas',[$state->id,$city->id]);
        }
    }
}
