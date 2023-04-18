<?php

namespace Modules\Api\Http\Controllers\Customer\Profile;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Profile\ProfileRepositoryInterface as CustomerProfileRepo;
use Modules\Api\Http\Requests\UpdateUserPhoneRequest;
use Modules\Api\Http\Requests\UpdateUserProfileDetailsRequest;

class ProfileController extends Controller
{
    public function __construct(CustomerProfileRepo $ProfileRepo,Request $request){
        $this->ProfileRepo = $ProfileRepo;
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }
     
    public function profileDetails(Request $request){
        $response = $this->ProfileRepo->getCustomerProfileDetails($request);
        return $response;
    }

    public function updateProfile(UpdateUserProfileDetailsRequest $request){
        return  $this->ProfileRepo->updateUserProfileDetails($request);
    }

    public function updatePhoneNumber(UpdateUserPhoneRequest $request){
        return  $this->ProfileRepo->updateCustomerPhoneNumber($request);
    }

    public function changePassword(Request $request){
        return  $this->ProfileRepo->changePassword($request);
    }
    public function accountDelete(Request $request){
         return  $this->ProfileRepo->accountDelete($request);
    }
    public function getSignedURL(Request $request){
        return  $this->ProfileRepo->getSignedURL($request);
    }
}
