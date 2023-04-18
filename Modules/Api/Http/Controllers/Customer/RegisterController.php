<?php

namespace Modules\Api\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Repositories\Customer\CustomerRegisterRepository as CustomerRegisterRepo;


class RegisterController extends Controller
{
    public $CustomerRegisterRepo;

    public function __construct(CustomerRegisterRepo $CustomerRegisterRepo)
    {
        $this->CustomerRegisterRepo =  $CustomerRegisterRepo;
    }

    public function register(Request $request)
    {
        $response = $this->CustomerRegisterRepo->register($request);
        return $response;
    }

    public function login(request $request)
    {

        $response = $this->CustomerRegisterRepo->login($request);
        return $response;
    }
    public function logout(Request $request)
    {
        $response = $this->CustomerRegisterRepo->logout($request);
        return $response;
    }
}
