<?php

namespace Modules\TrustedCustomers\Repositories;

use Modules\TrustedCustomers\Entities\TrustedCustomers;
use DB, Mail, Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class TrustedCustomersRepository implements TrustedCustomersRepositoryInterface
{
    public $TrustedCustomers;
    protected $model = 'TrustedCustomers';

    function __construct(TrustedCustomers $TrustedCustomers)
    {
        $this->TrustedCustomers = $TrustedCustomers;
    }

    public function getRecord($id)
    {
        return $this->TrustedCustomers->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->TrustedCustomers->findBySlug($slug);
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
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $status.$edit.$delete;
                })

                ->editColumn('name', function($list){
                    return \Illuminate\Support\Str::limit($list->name, 100, '');
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                }) 
                ->editColumn('status', function($list){
                    return ($list->status==1) ? '<span class="label label-success">'.trans('menu.active').'</span>' : '<span class="label label-danger">'.trans('menu.inactive').'</span>';
                })
                ->editColumn('image', function($list){
                    return '<a class="" href="'.$list->PicturePath.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->PicturePath.'"></a>';
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
            $filleable = $request->only( 'slug','name','designation','description','rating');
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $this->TrustedCustomers->fill($filleable);
            $this->TrustedCustomers->save();
            $response['message'] = trans('flash.success.trustedcustomers_created_successfully');
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
            $filleable = $request->only('slug','name','designation','description','rating');
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.trustedcustomers_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->TrustedCustomers->destroy($id);
    }

    public function changeStatus($request, $slug)
    {
        $TrustedCustomers = $this->getRecordBySlug($slug);
        if ($TrustedCustomers) {
            $id = $TrustedCustomers->id;
            $change = $this->TrustedCustomers->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->TrustedCustomers->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->TrustedCustomers->where('id', $id)
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

    public function saveTrustedCustomersPictureMedia($request)
    {
         $filename = uploadWithResize($request->file('files'), 'trustedcustomers/');
         $response['status'] = true;
         $response['filename'] = $filename;
         return $response;
    }
}
