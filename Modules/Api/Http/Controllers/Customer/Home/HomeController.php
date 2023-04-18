<?php

namespace Modules\Api\Http\Controllers\Customer\Home;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Home\HomeRepositoryInterface;

class HomeController extends Controller
{
    
     public function __construct(HomeRepositoryInterface $HomeRepo,Request $request){
        $this->HomeRepo = $HomeRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
     }

     public function searchProperty(Request $request){
        
     	return $this->HomeRepo->searchProperty($request);
       
     }
     
     public function getHomepageData(Request $request){
     	return $this->HomeRepo->getHomepageData($request);
     }
    public function getState(Request $request){
        return $this->HomeRepo->getState($request);
    }
    public function getCity(Request $request){
        return $this->HomeRepo->getCity($request);
    }
    public function getLocation(Request $request){
         return $this->HomeRepo->getLocation($request);
    }
    public function searchPropertyFilter(Request $request){
         return $this->HomeRepo->searchPropertyFilter($request);
    }
}
