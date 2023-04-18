<?php

namespace Modules\Api\Repositories;

interface RegisterRepositoryInterface
{
	public function Register($request);

    public function Login($request);
    
    public function sendResetLinkEmail($request);
    
}

