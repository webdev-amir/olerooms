<?php

namespace Modules\Payment\Http\Controllers\Frontend;

use Razorpay\Api\Api;
use Validator;
use Illuminate\Http\Request;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Http\Requests\CreatePaymentRequets;
use Illuminate\Routing\Controller;
use Modules\Payment\Repositories\Frontend\RozarPay\RozarPayPaymentRepositoryInterface as RozarPayPaymentRepository;
use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class RozarPayPaymentController extends \App\Http\Controllers\Controller
{
    public function __construct(RozarPayPaymentRepository $RozarPayPaymentRepository,CommonRepo $CommonRepo)
    {
        $this->CommonRepo = $CommonRepo;
        $this->RozarPayPaymentRepository = $RozarPayPaymentRepository;
    }

    public function paymentResponse(Request $request)
    {
        return $this->RozarPayPaymentRepository->makeBookingPaymentWithRozarPayResponse($request);
    }

    public function orderIdGenerate(Request $request){
        return $this->RozarPayPaymentRepository->orderIdGenerate($request);    
    }

     public function paymentWebhookResponse(Request $request)
    {
        return $this->RozarPayPaymentRepository->paymentWebhookResponse($request);
    }
}
