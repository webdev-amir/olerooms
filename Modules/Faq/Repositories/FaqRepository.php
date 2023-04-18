<?php

namespace Modules\Faq\Repositories;

use Modules\Faq\Entities\Faq;
use DB, Mail, Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class FaqRepository implements FaqRepositoryInterface
{

    public $Faq;
    protected $model = 'Faq';

    function __construct(Faq $Faq)
    {
        $this->Faq = $Faq;
    }

    public function getRecord($id)
    {
        return $this->Faq->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->Faq->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
                ->addColumn('action', function ($list) use ($model) {
                    $dispalyButton = displayButton(['deleteAjax' => [strtolower($model) . '.destroy', [$list->slug]], 'edit' => [strtolower($model) . '.edit', [$list->slug]], getStatusAI($list->status) => [strtolower($model) . '.status', [$list->slug]],]);
                    $status = $edit = $delete = '';
                    $status = keyExist($dispalyButton, getStatusAI($list->status));
                    //if(auth()->user()->checkPermissionTo(strtolower($model).'.edit','admin'))
                    $edit = keyExist($dispalyButton, 'edit');
                    //if(auth()->user()->checkPermissionTo(strtolower($model).'.destroy','admin'))
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $status . $edit . $delete;
                })
                ->editColumn('name', function ($list) {
                    return \Illuminate\Support\Str::limit($list->name, 100, '');
                })
                ->editColumn('created_at', function ($list) {
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                })
                ->editColumn('status', function ($list) {
                    return ($list->status == 1) ? '<span class="label label-success">' . trans('menu.active') . '</span>' : '<span class="label label-danger">' . trans('menu.inactive') . '</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug', 'question', 'answer');
            $this->Faq->fill($filleable);
            $this->Faq->save();
            $response['message'] = trans('flash.success.faq_created_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function update($request, $id)
    {
        try {
            $filleable = $request->only('slug', 'question', 'answer');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.faq_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->Faq->destroy($id);
    }


    public function changeStatus($request, $slug)
    {
        $Faq = $this->getRecordBySlug($slug);
        if ($Faq) {
            $id = $Faq->id;
            $change = $this->Faq->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->Faq->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->Faq->where('id', $id)
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
}
