<?php

namespace Modules\Api\Http\Controllers\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\Profile\ProfileRepositoryInterface as CustomerProfileRepo;

class GuestCommonController extends Controller
{
    public function __construct(CustomerProfileRepo $ProfileRepo,Request $request){
        $this->ProfileRepo = $ProfileRepo;
    }
     
    public function getProfileFormData(Request $request){
        $response = $this->ProfileRepo->getProfileFormData($request);
        return $response;
    }
}
