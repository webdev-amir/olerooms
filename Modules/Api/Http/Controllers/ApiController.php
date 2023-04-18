<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;
use App\Models\User;
use Modules\Login\Entities\OtpVerification;
use Modules\Api\Repositories\RegisterRepositoryInterface;


class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
   
    public function __construct(RegisterRepositoryInterface $registerRepo) 
    {
        $this->registerRepo =  $registerRepo;
    }
     public function Register(Request $request){
        
         
        
         $response = $this->registerRepo->Register($request);
      
       
        return $response;
        
        

     }
     public function Login(request $request){
         $response = $this->registerRepo->Login($request);
      
       
        return $response;

     }
}
