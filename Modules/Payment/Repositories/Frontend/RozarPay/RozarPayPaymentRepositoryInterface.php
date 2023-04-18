<?php

namespace Modules\Payment\Repositories\Frontend\RozarPay;


interface RozarPayPaymentRepositoryInterface
{
    public function makeBookingPaymentWithRozarPayResponse($request);
    
    public function paymentWebhookResponse($request);
}