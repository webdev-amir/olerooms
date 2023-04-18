<?php

namespace Modules\City\Repositories\StateRepository;

use Modules\City\Entities\State;
use DB, Mail, Session;
use config;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;

class StateRepository implements StateRepositoryInterface
{

    public $State;
    protected $model = 'State';

    function __construct(State $State)
    {
        $this->State = $State;
    }

    public function getRecord($id)
    {
        return $this->State->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->State->findBySlug($slug);
    }

    public function update($request, $id)
    {
        try {
            $filleable = $request->only('name', 'stateCode', 'status');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = 'State Updated Successfully';
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->State->destroy($id);
    }

    public function changeStateStatus($request, $id)
    {
        $state = $this->State->find($id);
        if ($state) {
            $id = $state->id;
            $active = $state->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $state->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $state->where('id', $id)->update($update_arr);
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

    public function getAllRecordsWithFilter($request)
    {
        $states = $this->State;
        $status = NULL;
        if ($request->get('status')) {
            $status = $request->get('status');
            $filterStatus = ($status == 'active') ? $filterStatus = 1 : $filterStatus = 0;
            $states = $states->where('status', $filterStatus);
        }
        if ($request->get('search')) {
            $searchKey = $request->get('search');
            $states = $states->where('name', 'like', '%' . $searchKey . '%');
        }
        return $states->orderBy('status', 'desc')->where('country_id', 101)->orderBy('name', 'asc')->paginate(config::get('custom.default_pagination'));
    }
}
