<?php

namespace Modules\City\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\City\Http\Requests\UpdateStateRequest;
use Modules\City\Http\Requests\BannerImageMediaRequest;
use Modules\City\Repositories\StateRepository\StateRepositoryInterface as StateRepo;
use DB, Response, View;
use Session;

class StateController extends Controller
{
    public function __construct(StateRepo $StateRepo)
    {
        $this->middleware(['ability','auth']);
        $this->StateRepo = $StateRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $records = $this->StateRepo->getAllRecordsWithFilter($request);
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('city::state.ajax_state_list',compact('records'))->withModel('state')->render())));
        }
        return view('city::state.index',compact('records'))->withModel('state');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$id)
    {
        $data =  $this->StateRepo->getRecord($id);
        if($data){
          return view('city::state.edit',compact('data'))->withModel('state');  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('state.index');
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateStateRequest $request, $id)
    {
        $data =  $this->StateRepo->getRecord($id);
        if($data){
            $response = $this->StateRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('state.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('state.index');
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->StateRepo->getRecord($id);
            if($data){
                $this->StateRepo->destroy($id);
                Session::flash('success','State deleted Successfully');
                return redirect()->route('state.index');
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('state.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('state.index');
        }
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $data = $this->StateRepo->getRecord($id);
            if($data){
                $response = $this->StateRepo->changeStateStatus($request, $id);
                if($request->ajax()){
                    return response()->json($response);
                }
                Session::flash('success','State status is updated successfully');
                return redirect()->route('state.index');
            }
        }catch (QueryException $e){
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('state.index');
        }
    }
}
