<?php

namespace Modules\Cron\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cron\Repositories\CronRepositoryInterface as CronRepo;

class CronController extends Controller
{
    public function __construct(CronRepo $CronRepo)
    {
        $this->CronRepo = $CronRepo;
    }

    public function sendTestMailandNotificationCron()
    {
        $response = $this->CronRepo->sendTestMailandNotificationCron();
        return response()->json($response);
    }

    public function makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor()
    { 
        $response = $this->CronRepo->makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor();
         return response()->json($response);
    } 

    public function makeConfirmedToCompletedIfBookingCheckOutDateIsPassed()
    { 
        $response = $this->CronRepo->makeConfirmedToCompletedIfBookingCheckOutDateIsPassed();
        return response()->json($response);
    }

    public function makeSearchAddressForSearch()
    {
        $response = $this->CronRepo->makeSearchAddressForSearch();
        return response()->json($response);
    }
}
