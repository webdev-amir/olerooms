<?php

namespace Modules\PropertyType\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PropertyType\Http\Requests\CreatePropertyTypeRequest;
use Modules\PropertyType\Http\Requests\UpdatePropertyTypeRequest;
use Modules\PropertyType\Http\Requests\PropertyTypeMediaRequest;
use Modules\PropertyType\Repositories\PropertyTypeRepositoryInterface as PropertyTypeRepo;

class PropertyTypeController extends Controller
{
    protected $model = 'PropertyType';

    public function __construct(PropertyTypeRepo $PropertyTypeRepo)
    {
        $this->middleware(['auth','ability'])->except('getAjaxData');
        $this->PropertyTypeRepo = $PropertyTypeRepo;
    }
    
        /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(request()->ajax())
        {
            return $this->PropertyTypeRepo->getAjaxData($request);
        }
        return view('propertytype::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('propertytype::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreatePropertyTypeRequest $request)
    {
        $response = $this->PropertyTypeRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('propertytype.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->PropertyTypeRepo->getRecordBySlug($slug);
        if($data){
          return view('propertytype::edit',compact('data'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('propertytype.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdatePropertyTypeRequest $request, $id)
    { 
        $data =  $this->PropertyTypeRepo->getRecord($id);
        if($data){
            $response = $this->PropertyTypeRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('propertytype.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('propertytype.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->PropertyTypeRepo->getRecordBySlug($slug);
            if($data){
                $this->PropertyTypeRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.propertytype_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('propertytype.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('propertytype.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('propertytype.index');
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
    public function status(Request $request,$slug)
    {   
        $response = $this->PropertyTypeRepo->changeStatus($request,$slug);
        if($request->ajax()){
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
    public function saveMedia(PropertyTypeMediaRequest $request) {
        try {
            $response = $this->PropertyTypeRepo->savePropertyTypePictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
