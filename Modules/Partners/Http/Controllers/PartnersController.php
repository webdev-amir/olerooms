<?php

namespace Modules\Partners\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Partners\Entities\Partners;
use Modules\Partners\Http\Requests\CreatePartnersRequest;
use Modules\Partners\Http\Requests\UpdatePartnersRequest;
use Modules\Partners\Http\Requests\PartnersMediaRequest;
use Modules\Partners\Repositories\PartnersRepositoryInterface as PartnersRepo;

class PartnersController extends Controller {

    protected $model = 'Partners';

    public function __construct(PartnersRepo $PartnersRepo)
    {
        $this->middleware(['ability','auth']);
        $this->PartnersRepo = $PartnersRepo;
    }

    public function index(Request $request) {

        if(request()->ajax()) 
        {
            return $this->PartnersRepo->getAjaxData($request);
        }
        return view('partners::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {

        return view('partners::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreatePartnersRequest $request) {

        $response = $this->PartnersRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('partners.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->PartnersRepo->getRecordBySlug($slug);
        if($data){
          return view('partners::edit',compact('data'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('partners.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdatePartnersRequest $request, $id)
    { 
        $data =  $this->PartnersRepo->getRecord($id);
        if($data){
            $response = $this->PartnersRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('partners.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('partners.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->PartnersRepo->getRecordBySlug($slug);
            if($data){
                $this->PartnersRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.partners_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('partners.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('partners.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('partners.index');
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
        $response = $this->PartnersRepo->changeStatus($request,$slug);
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
    public function saveMedia(PartnersMediaRequest $request) {
        try {
            $response = $this->PartnersRepo->savePartnersPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
