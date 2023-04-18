<?php

namespace Modules\Coupon\Repositories;

use Modules\Coupon\Entities\Coupon;
use DB, Session;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Modules\PropertyType\Entities\PropertyType;

class CouponRepository implements CouponRepositoryInterface
{
    public $Coupon;
    protected $model = 'Coupon';

    function __construct(Coupon $Coupon, PropertyType $PropertyType)
    {
        $this->Coupon = $Coupon;
        $this->PropertyType = $PropertyType;
    }

    public function getRecord($id)
    {
        return $this->Coupon->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->Coupon->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
           DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model; 
             $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
               ->addColumn('action', function($list) use($model){
                    $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],getStatusAI($list->status)=>[strtolower($model).'.status',[$list->slug]],]);
                    $status = $edit = $delete = '';
                    $status = keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $status.$edit.$delete;
                })
                ->editColumn('title', function($list){
                    return \Illuminate\Support\Str::limit($list->title, 100, '');
                })
                ->editColumn('coupon_code', function($list){
                    return \Illuminate\Support\Str::limit($list->coupon_code, 100, '');
                })
                ->editColumn('offer_type', function($list){
                    return \Illuminate\Support\Str::limit($list->offer_type, 100, '');
                })
                ->editColumn('property_type_id', function($list){
                    return $list->propertyType->name;
                })
                ->editColumn('duration', function($list){
                    return $list->start_date.' to '.$list->end_date;
                })
                ->editColumn('coupon_status', function($list){
                    if($list->start_date > Carbon::now()){
                        $couponStatus = 'Upcoming';
                    }elseif($list->end_date < Carbon::now()){
                        $couponStatus = 'Expired';
                    }elseif(Carbon::now()->between($list->start_date, $list->end_date)) {
                        $couponStatus = 'Ongoing';
                    }else{
                        $couponStatus = 'N/A';
                    }
                    return $couponStatus;
                })  
                ->editColumn('is_global_apply', function($list){
                    return ($list->is_global_apply) ? 'YES' : 'NO';
                }) 
                ->editColumn('image', function ($list) {
                    return '<a class="" href="' . $list->PicturePath . '" data-lightbox="example-1"><img class="" style="width: 60px;" src="' . $list->PicturePath . '"></a>';
                })
                ->editColumn('status', function($list){
                    return ($list->status==1) ? '<span class="label label-success">'.trans('menu.active').'</span>' : '<span class="label label-danger">'.trans('menu.inactive').'</span>';
                })
                ->rawColumns(['status','action','image'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','title','amount','offer_type','coupon_code','property_type_id','description','start_date','end_date');
            // $filleable['start_date'] = Carbon::parse($request->get('start_date'))->format('Y-m-d');
            // $filleable['end_date'] = Carbon::parse($request->get('end_date'))->format('Y-m-d');
            ($request->get('is_global_apply')) ? $filleable['is_global_apply'] = $request->get('is_global_apply') : $filleable['is_global_apply'] = 0;
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $this->Coupon->fill($filleable);
            $this->Coupon->save();
            $response['message'] = trans('flash.success.coupon_created_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
        return $response;
    }

    public function update($request, $id)
    {
        try {
            $filleable = $request->only('slug','title','amount','offer_type','coupon_code','description','start_date','end_date','is_global_apply');
            ($request->get('is_global_apply')) ? $filleable['is_global_apply'] = $request->get('is_global_apply') : $filleable['is_global_apply'] = 0;
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.coupon_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->Coupon->destroy($id);
    }

    public function changeStatus($request, $slug)
    {
        $Coupon = $this->getRecordBySlug($slug);
        if ($Coupon) {
            $id = $Coupon->id;
            $change = $this->Coupon->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->Coupon->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->Coupon->where('id', $id)
                        ->update($update_arr);
                }
                $message = trans('flash.success.status_updated_successfully');
                $type = 'success';
            } else {
                $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                $type = 'warning';
            }
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'warning';
        }
        $response['status_code'] = 200;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }

    public function getPropertyType($request)
    {   
        return $this->PropertyType->where('status',1)->get()->toArray();
    }

    public function saveNewsUpdatesPictureMedia($request)
    {
        $filename = uploadWithResize($request->file('files'),  'coupon/');
        $response['status'] = true;
        $response['filename'] = $filename;
        return $response;
    }
}
