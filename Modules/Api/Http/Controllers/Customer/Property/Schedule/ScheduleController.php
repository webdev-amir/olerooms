<?php

namespace Modules\Api\Http\Controllers\Customer\Property\Schedule;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Property\Schedule\ScheduleRepositoryInterface as ScheduleRepo;


class ScheduleController extends Controller
{
    public function __construct(ScheduleRepo $ScheduleRepo, Request $request)
    {
        $this->ScheduleRepo = $ScheduleRepo;
        /* if($request->headers->get('IsGguest')=='false')*/
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    public function addPropertySchedule(Request $request)
    {
        $response = $this->ScheduleRepo->addPropertySchedule($request);
        return $response;
    }

    public function updatePropertySchedule(Request $request)
    {
        $response = $this->ScheduleRepo->updatePropertySchedule($request);
        return $response;
    }

    public function getScheduleProperty(Request $request)
    {
        $response = $this->ScheduleRepo->getScheduleProperty($request);
        return $response;
    }

    public function deleteScheduleProperty(Request $request)
    {
        $response = $this->ScheduleRepo->deleteScheduleProperty($request);
        return $response;
    }

    public function myVisits(Request $request)
    {
        $response = $this->ScheduleRepo->myVisits($request);
        return $response;
    }

    public function visitPropertyDetail(Request $request)
    {
        $response = $this->ScheduleRepo->visitPropertyDetail($request);
        return $response;
    }

    public function cancelScheduleMyVisit(Request $request)
    {
        $response = $this->ScheduleRepo->cancelScheduleMyVisit($request);
        return $response;
    }
}
