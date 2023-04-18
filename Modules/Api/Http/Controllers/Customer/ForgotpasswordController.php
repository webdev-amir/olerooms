<?php

namespace Modules\Api\Http\Controllers\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Foundation\Auth\ApiSendsPasswordResetEmails;
use App\Http\Controllers\Api\BaseController;
use Modules\Api\Repositories\RegisterRepositoryInterface as RegisterRepo;


class ForgotpasswordController extends BaseController
{

    use ApiSendsPasswordResetEmails;

    public function __construct(User $User, RegisterRepo $RegisterRepo)
    {
        $this->User = $User;
        $this->RegisterRepo =  $RegisterRepo;
    }

    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }


    public function sendResetLinkEmail(Request $request)
    {
        $response = $this->RegisterRepo->sendResetLinkEmail($request);
        return $response;
    }
}
