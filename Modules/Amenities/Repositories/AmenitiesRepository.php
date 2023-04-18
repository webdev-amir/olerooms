<?php

namespace Modules\Amenities\Repositories;

use Modules\Amenities\Entities\Amenities;
use DB, Mail, Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;

class AmenitiesRepository implements AmenitiesRepositoryInterface
{

    protected $model = 'Amenities';

    function __construct(Amenities $Amenities)
    {
        $this->Amenities = $Amenities;
    }

    public function getRecord($id)
    {
        return $this->Amenities->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->Amenities->findBySlug($slug);
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
                ->editColumn('image', function ($list) {
                    return '<a class="" href="' . $list->PicturePath . '" data-lightbox="example-1"><img class="" style="width: 60px;" src="' . $list->PicturePath . '"></a>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug', 'name');
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $this->Amenities->fill($filleable);
            $this->Amenities->save();
            $response['message'] = trans('flash.success.amenities_created_successfully');
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
            $filleable = $request->only('slug', 'name');
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.amenities_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        $record = $this->Amenities->find($id);
        if ($record) {
            return $this->Amenities->destroy($id);
        }
        return false;
    }

    public function changeStatus($request, $slug)
    {
        $Amenity = $this->getRecordBySlug($slug);
        if ($Amenity) {
            $id = $Amenity->id;
            $change = $this->Amenities->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->Amenities->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->Amenities->where('id', $id)
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

    public function saveAmenitiesPictureMedia($request)
    {
        $filename = uploadWithResize($request->file('files'), 'amenity/');
        $response['status'] = true;
        $response['filename'] = $filename;
        return $response;
    }
}
