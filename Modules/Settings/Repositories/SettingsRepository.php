<?php

namespace Modules\Settings\Repositories;

use Modules\Settings\Entities\Settings;
use DB, Mail, Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class SettingsRepository implements SettingsRepositoryInterface
{

    public $Settings;
    protected $model = 'Settings';

    function __construct(Settings $Settings)
    {
        $this->Settings = $Settings;
    }

    public function getRecord($id)
    {
        return $this->Settings->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->Settings->where('slug', $slug)->first();
    }
    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('is_show', 1)->latest()->get();
            return DataTables::of($lists)
                ->addColumn('action', function ($list) use ($model) {
                    $dispalyButton = displayButton(['deleteAjax' => [strtolower($model) . '.destroy', [$list->id]], 'edit' => [strtolower($model) . '.edit', [$list->slug]]]);
                    $edit = $delete = '';
                    $edit = keyExist($dispalyButton, 'edit');
                    // $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $edit;
                })
                ->editColumn('name', function ($list) {
                    return \Illuminate\Support\Str::limit(ucfirst($list->name), 100, '...');
                })
                ->editColumn('val', function ($list) {
                    if ($list->group == 'upload-document') {
                        return '<a href="' . route('downloads3file') . '?fp=' . $list->S3AggrementPdfDownloadPath . '" download="Agreement"> ' . $list->val . '</a>';
                    } else if ($list->slug == 'auto-confirmation' || $list->slug == 'booking-rejection-time') {
                        return $list->val . ' Minutes';
                    } else if ($list->slug == 'comission-percentage') {
                        return $list->val . '%';
                    } else if ($list->slug == 'schedule-cancelled-before-time' || $list->slug == 'booking-cancelled-before-time') {
                        return $list->val . ' Hours';
                    } else {
                        return $list->val;
                    }
                })
                ->editColumn('created_at', function ($list) {
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                })
                ->rawColumns(['action', 'val'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('name', 'val');
            $this->Settings->fill($filleable);
            $this->Settings->save();
            $response['message'] = trans('flash.success.settings_created_successfully');
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
            $filleable = $request->only('name', 'val');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.settings_updated_successfully');
            $response['type'] = 'success';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function destroy($id)
    {
        return $this->Settings->destroy($id);
    }

    public function changeStatus($request, $slug)
    {
        $Settings = $this->getRecordBySlug($slug);
        if ($Settings) {
            $id = $Settings->id;
            $change = $this->Settings->find($id);
            $active = $change->status;
            if ($id != null) {
                if ($active == 1) {
                    $update_arr = array('status' => 0);
                    $this->Settings->where('id', $id)->update($update_arr);
                } else {
                    $update_arr = array('status' => 1);
                    $this->Settings->where('id', $id)
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

    public function saveBannerImageMedia($request)
    {
        $path = uploadOnS3Bucket($request->file('files'), 'settings/');
        $response['status'] = true;
        $response['status_code'] = 200;
        $response['filename'] = $path;
        $response['message'] = 'Document uploaded successfully';
        $response['type'] = 'success';

        return $response;
    }
}
