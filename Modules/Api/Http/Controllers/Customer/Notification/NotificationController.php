<?php

namespace Modules\Api\Http\Controllers\Customer\Notification;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Notification\NotificationRepositoryInterface as NotificationRepo;
use Modules\Api\Http\Requests\UpdateUserPhoneRequest;
use Modules\Api\Http\Requests\UpdateUserProfileDetailsRequest;

class NotificationController extends Controller
{
    public function __construct(NotificationRepo $NotificationRepo,Request $request){
        $this->NotificationRepo = $NotificationRepo;
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }
     
    public function notificationList(Request $request){
    	
        $response = $this->NotificationRepo->notificationList($request);
        return $response;
    }

   
}
