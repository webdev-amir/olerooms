<?php

namespace Modules\Payment\Http\Controllers\Frontend;

use Razorpay\Api\Api;
use Validator;
use Illuminate\Http\Request;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Http\Requests\CreatePaymentRequets;
use Illuminate\Routing\Controller;
use Modules\Payment\Repositories\Frontend\RozarPay\RozarPayCompanyPaymentRepository as RozarPayCompanyPaymentRepository;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class RozarPayCompanyPaymentController extends \App\Http\Controllers\Controller
{
    public function __construct(RozarPayCompanyPaymentRepository $RozarPayCompanyPaymentRepository, CommonRepo $CommonRepo)
    {
        $this->CommonRepo = $CommonRepo;
        $this->RozarPayCompanyPaymentRepository = $RozarPayCompanyPaymentRepository;
    }

    public function paymentResponse(Request $request)
    {
        return $this->RozarPayCompanyPaymentRepository->makeBookingPaymentWithRozarPayResponse($request);
    }

    public function orderIdGenerate(Request $request)
    {
        return $this->RozarPayCompanyPaymentRepository->orderIdGenerate($request);
    }

    public function paymentWebhookResponse(Request $request)
    {
        return $this->RozarPayCompanyPaymentRepository->paymentWebhookResponse($request);
    }
}
