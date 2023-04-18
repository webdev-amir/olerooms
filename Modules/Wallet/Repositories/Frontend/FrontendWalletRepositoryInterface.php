<?php

namespace Modules\Wallet\Repositories\Frontend;


interface FrontendWalletRepositoryInterface
{
    public function getWalletTempRecordForPayment($slug);
    
    public function getWalletTempRecordById($id);

    public function paystackChargePaymentAndAddMoneyInWallet($request);

    public function getMywalletPaymentSummaryList($request);
}