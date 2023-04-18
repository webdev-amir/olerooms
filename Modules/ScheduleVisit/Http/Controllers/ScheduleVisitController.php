<?php

namespace Modules\ScheduleVisit\Http\Controllers;

use Session, View, Auth, Response, config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;
use Modules\ScheduleVisit\Repositories\Backend\ScheduleVisitRepositoryInterface as ScheduleVisitRepo;

class ScheduleVisitController extends Controller
{
    protected $model = 'ScheduleVisit';
    public function __construct(ScheduleVisitRepo $ScheduleVisitRepo)
    {
        $this->middleware(['auth', 'verified', 'prevent-back-history']);
        $this->ScheduleVisitRepo = $ScheduleVisitRepo;
    }

    public function index(Request $request)
    {
        $records = $this->ScheduleVisitRepo->getAllRecords($request);
        if ($request->ajax()) {
            return Response::json(array('page'=>$request->get('page'),'body' =>json_encode(View::make('schedulevisit::ajax_schedule_visit_list',compact('records'))->withModel(strtolower($this->model))->render())));
        }
        return view('schedulevisit::index',compact('records'))->withModel(strtolower($this->model));
    }

    public function schedulevisitDetails($visit_slug)
    {
        $data = $this->ScheduleVisitRepo->visitDetailsRecord($visit_slug);
        if ($data) {
            return view('schedulevisit::.visit_details', compact('data'))->withModel('propertyownerdashboard');
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('adminschedulevisit.index');
    }
}
