<?php

namespace Modules\Api\Repositories\Customer;


interface CustomerRegisterRepositoryInterface{
    public function register($request);

    public function login($request);
    
    public function logout($request);
}

