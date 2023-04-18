<?php

namespace Modules\ScheduleVisit\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Session, View, Response, config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ScheduleVisit\Repositories\Frontend\MyScheduleVisitRepositoryInterface as MyScheduleVisitRepository;

class ScheduleVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(MyScheduleVisitRepository $MyScheduleVisitRepo)
    {
        $this->MyScheduleVisitRepo = $MyScheduleVisitRepo;
    }
    public function index($slug=NULL)
    {
        $scheduleVisit = $this->MyScheduleVisitRepo->getScheduledProperty($slug);
        if($scheduleVisit){
           return view('schedulevisit::frontend.index', compact('scheduleVisit','slug')); 
        }
        abort(404); 
    }

    public function storeScheduleVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitRepo->storeScheduleVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function updateScheduleVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitRepo->updateScheduleVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function deleteVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitRepo->deleteVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function success($slug = ''){
        
        $scheduleVisit = $this->MyScheduleVisitRepo->getScheduledProperty($slug);
        if($scheduleVisit){
           return view('schedulevisit::frontend.success', compact('scheduleVisit')); 
        }
        abort(404); 
    }
}
