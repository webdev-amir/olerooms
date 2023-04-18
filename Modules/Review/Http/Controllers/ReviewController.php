<?php

namespace Modules\Review\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\PropertyType\Repositories\PropertyTypeRepositoryInterface as PropertyType;
use Modules\Review\Repositories\Backend\ReviewRepositoryInterface as ReviewRepo;

class ReviewController extends Controller
{
    protected $model = 'Review';
    public function __construct(ReviewRepo $ReviewRepo, PropertyType $PropertyType)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->ReviewRepo = $ReviewRepo;   
        $this->PropertyType = $PropertyType;
    }

    public function index(Request $request)
    {
        $vendors = $this->ReviewRepo->getVendors();
        $propertyTypes = $this->PropertyType->getAllRecord();
        if(request()->ajax()) 
        {
            return $this->ReviewRepo->getAjaxData($request);
        }
        return view('review::backend.index',compact('vendors','propertyTypes'))->withModel(strtolower($this->model));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$id)
    {
        try{
            $data =  $this->ReviewRepo->getRecord($id);
            if($data){
                $this->ReviewRepo->destroy($id);
                $type = 'success'; $message = 'Review deleted successfully';
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('review.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('review.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('review.index');
        }
    }
}
