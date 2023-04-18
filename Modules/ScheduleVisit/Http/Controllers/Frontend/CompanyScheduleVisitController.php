<?php

namespace Modules\ScheduleVisit\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Session, View, Response, config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ScheduleVisit\Repositories\Frontend\MyScheduleVisitCompanyRepository as MyScheduleVisitCompanyRepository;

class CompanyScheduleVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct(MyScheduleVisitCompanyRepository $MyScheduleVisitCompRepo)
    {
        $this->MyScheduleVisitCompRepo = $MyScheduleVisitCompRepo;
    }
    public function index($slug=NULL)
    {
        $scheduleVisit = $this->MyScheduleVisitCompRepo->getScheduledProperty($slug);
        if($scheduleVisit){
           return view('schedulevisit::frontend.company.index', compact('scheduleVisit','slug')); 
        }
        abort(404); 
    }

    public function storeScheduleVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitCompRepo->storeScheduleVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function updateScheduleVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitCompRepo->updateScheduleVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function deleteVisit(Request $request){
        try {
            $response = $this->MyScheduleVisitCompRepo->deleteVisit($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        
    }
    public function success($slug = ''){
        
        $scheduleVisit = $this->MyScheduleVisitCompRepo->getScheduledProperty($slug);
        if($scheduleVisit){
           return view('schedulevisit::frontend.company.success', compact('scheduleVisit')); 
        }
        abort(404); 
    }
}
