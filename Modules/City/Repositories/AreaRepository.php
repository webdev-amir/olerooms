<?php

namespace Modules\City\Repositories;

use Modules\City\Entities\Area;
use DB,Mail,Session, Config;
use DataTables;
use Illuminate\Support\Facades\Input;
use Modules\City\Entities\City;

class AreaRepository implements AreaRepositoryInterface {

    public $Area;
    protected $model = 'Area';

    function __construct(Area $Area) {
        $this->Area = $Area;
    }

    public function getRecord($id)
    {
      return $this->Area->find($id);
    }

    public function getCity($id)
    {
        $city  = City::find($id);
        return $city;
    }

    public function getRecordBySlug($slug,$id)
    {
        return $this->Area->where('slug',$slug)->where('city_id',$id)->first();
    }

    public function getAjaxDataRecords($request, $id)
    {
        $areas = $this->Area->where('city_id',$id);
        $status = NULL;
        if ($request->get('status')) {
            $status = $request->get('status');
            $filterStatus = ($status == 'active') ? $filterStatus = 1 : $filterStatus = 0;
            $areas = $areas->where('status',$filterStatus);
        }
        if ($request->get('search')) {
            $searchKey = $request->get('search');
            $areas = $areas->where('name', 'like', '%' . $searchKey . '%');
        }
        return $areas->orderBy('id','asc')->paginate(config::get('custom.default_pagination'));
    
    }

    public function getAjaxData($request, $id)
    {
        try {
           DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('city_id', $id)->get();
            return DataTables::of($lists)
            ->addColumn('action', function ($list) use ($model) {
                $dispalyButton = displayButton(['deleteAjax' => [strtolower($model) . '.destroy', [$list->city_id,$list->slug]],'edit' => ['state.'. strtolower($model) . '.edit', [$list->city->state->id, $list->city_id,$list->slug]]]);
                $edit = $delete = '';
                $edit = keyExist($dispalyButton, 'edit');
                $delete = keyExist($dispalyButton, 'deleteAjax');
                return $edit . $delete;
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
        } 
        catch (Exception $ex) {
            return false;
        }        
    }
    
    public function store($request,$id)
    {
        try {
            $city_id = $id;
            $filleable = $request->only('name','city_id');
            $this->Area->fill($filleable);
            $this->Area->save();
            $response['message'] = 'area created successfully';
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function update($request, $id,$areaId)
    {
        try {
            $filleable = $request->only('slug', 'name');
            $record = $this->getRecord($areaId);
            $record->fill($filleable);
            $record->save();
            $response['message'] = "Area Updated Successfully";
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        $record = $this->Area->find($id);
        if ($record) {
            return $this->Area->destroy($id);
        }
        return false;
    }

    public function changeAreaStatus($request, $id, $slug)
    {
        $area = $this->Area->Where('slug',$slug)->first();
        if ($area) {
            $id = $area->id;
            $active = $area->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $area->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $area->where('id', $id)->update($update_arr);
                }                
                $message = trans('flash.success.status_updated_successfully');
                $type = 'success';                
            } else {
                $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                $type = 'warning';
            }            
        } else {
            $message =  trans('flash.error.oops_something_went_wrong_updating_record');
            $type = 'error';
        }
        $response['status'] = true;
        $response['message'] = $message;
        $response['type'] = $type;
        return $response;
    }
}
