<?php

namespace Modules\City\Repositories;

use Modules\City\Entities\City;
use DB, Mail, Session;
use config;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;

class CityRepository implements CityRepositoryInterface
{

    public $City;
    protected $model = 'City';

    function __construct(City $City)
    {
        $this->City = $City;
    }

    public function getRecord($id)
    {
        return $this->City->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->City->findBySlug($slug);
    }

    public function getAjaxDataRecords($request, $id)
    {
        $cities = $this->City->whereHas('author', function (Builder $q) {
        })->where('cities.state_id', $id);
        $filterStatus = NULL;
        if ($request->get('status')) {
            $status = $request->get('status');
            if ($status == 'active') {
                $filterStatus = 1;
                $cities = $cities->where('cities.status', 'like', '%' . $filterStatus . '%');
            } elseif ($status == 'inactive') {
                $filterStatus = 0;
                $cities = $cities->where('cities.status', 'like', '%' . $filterStatus . '%');
            } else {
                $filterStatus = 2;
                $cities = $cities->where('cities.status', 'like', '%' . $filterStatus . '%');
            }
        }
        if ($request->get('search')) {
            $searchKey = $request->get('search');
            $cities = $cities->where('name', 'like', '%' . $searchKey . '%');
        }
        return $cities->orderBy('status', 'desc')->orderBy('name', 'asc')->paginate(config::get('custom.default_pagination'));
    }


    public function store($request, $stateId)
    {
        try {
            $filleable = $request->only('name', 'status');
            $filleable['state_id'] = $stateId;
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $this->City->fill($filleable);
            $this->City->save();
            $response['message'] = 'City created successfully';
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
            $filleable = $request->only('name', 'status');
            $record = $this->getRecord($id);
            $filleable['city_image'] = '';
            if ($request->get('image')) $filleable['image'] = $request->get('image');
            $record->fill($filleable);
            $record->save();
            $response['message'] = 'City Updated Successfully';
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }


    public function destroy($id)
    {
        return $this->City->destroy($id);
    }

    public function changeCityStatus($request, $id)
    {
        $city = $this->City->find($id);
        if ($city) {
            $id = $city->id;
            $active = $city->status;
            if ($id != null) {
                if ($active == 0) {
                    $update_arr = array('status' => 2);
                    $city->where('id', $id)->update($update_arr);
                } elseif ($active == 2) {
                    $update_arr = array('status' => 1);
                    $city->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 0);
                    $city->where('id', $id)->update($update_arr);
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

    public function saveBannerImageMedia($request)
    {
        $filename = uploadWithResize($request->file('files'), '/city/');
        
        $response['status'] = true;
        $response['filename'] = $filename;
        return $response;
    }
}
