<?php
namespace Modules\Amenities\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Amenities\Http\Requests\AmenitiesMediaRequest;
use Modules\Amenities\Http\Requests\CreateAmenitiesRequest;
use Modules\Amenities\Http\Requests\UpdateAmenitiesRequest;
use Modules\Amenities\Repositories\AmenitiesRepositoryInterface as AmenitiesRepo;

class AmenitiesController extends Controller
{
    protected $model = 'Amenities';

    public function __construct(AmenitiesRepo $AmenitiesRepo)
    {
        $this->middleware(['ability','auth'],['except' => ['store','create']]);
        $this->AmenitiesRepo = $AmenitiesRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    { 
        if(request()->ajax()) 
        {
            return $this->AmenitiesRepo->getAjaxData($request);
        }
        return view('amenities::index')->withModel(strtolower($this->model));
    }

    public function create()
    {
        $rating = 1;
        return view('amenities::create',compact('rating'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateAmenitiesRequest $request)
    {
        $response = $this->AmenitiesRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('amenities.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($slug)
    {
        $data =  $this->AmenitiesRepo->getRecordBySlug($slug);
        if($data){
          $rating = $data->rating;
          return view('amenities::edit',compact('data','rating'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('amenities.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateAmenitiesRequest $request, $id)
    { 
        $data =  $this->AmenitiesRepo->getRecord($id);
        if($data){
            $response = $this->AmenitiesRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('amenities.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('amenities.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->AmenitiesRepo->getRecordBySlug($slug);
            if($data){
                $this->AmenitiesRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.amenities_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('amenities.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('amenities.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('amenities.index');
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
        $response = $this->AmenitiesRepo->changeStatus($request,$slug);
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
    public function saveMedia(AmenitiesMediaRequest $request) {
        try {
            $response = $this->AmenitiesRepo->saveAmenitiesPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
