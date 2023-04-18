<?php

namespace Modules\Coupon\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Http\Requests\CreateCouponRequest;
use Modules\Coupon\Http\Requests\UpdateCouponRequest;
use Modules\Coupon\Http\Requests\CouponMediaRequest;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\Coupon\Repositories\CouponRepositoryInterface as CouponRepo;

class CouponController extends Controller
{
    protected $model = 'Coupon';

    public function __construct(CouponRepo $CouponRepo, CommonRepo $CommonRepo)
    {
        $this->middleware(['ability','auth']);
        $this->CouponRepo = $CouponRepo;
        $this->CommonRepo = $CommonRepo;
    }

    public function index(Request $request) {

        if(request()->ajax()) 
        {
            return $this->CouponRepo->getAjaxData($request);
        }
        return view('coupon::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request) {
        $propertyType = $this->CommonRepo->getPropertyTypesPluck($request);
        return view('coupon::create',compact('propertyType'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateCouponRequest $request) 
    {
        $response = $this->CouponRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('coupon.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $slug)
    {
        $data =  $this->CouponRepo->getRecordBySlug($slug);
        $propertyType = $this->CommonRepo->getPropertyTypesPluck($request);
        if($data){
          return view('coupon::edit',compact('data','propertyType'))->withModel(strtolower($this->model));
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('coupon.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateCouponRequest $request, $id)
    { 
        $data =  $this->CouponRepo->getRecord($id);
        if($data){
            $response = $this->CouponRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('coupon.index');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('coupon.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $data =  $this->CouponRepo->getRecordBySlug($slug);
            if($data){
                $this->CouponRepo->destroy($data->id);
                $type = 'success'; $message = trans('flash.success.coupon_deleted_successfully');
                if($request->ajax()){
                    return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
                }
                Session::flash($type, $message);
                return redirect()->route('coupon.index');
            }
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.oops_reocrd_not_available')]);
            }
            Session::flash('error', trans('flash.error.oops_reocrd_not_available'));
            return redirect()->route('coupon.index');
        }catch (QueryException $e){
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>'error','message' => trans('flash.error.cant_delete_reocrd_try_later')]);
            }
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('coupon.index');
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
        $response = $this->CouponRepo->changeStatus($request,$slug);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }

    public function saveMedia(CouponMediaRequest $request)
    {
        try {
            $response = $this->CouponRepo->saveNewsUpdatesPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
