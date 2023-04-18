<?php

namespace Modules\Wallet\Repositories\Backend\RedeemCredit;


interface RedeemCreditRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function getAllRecords($request);
    
    public function updateRedeemStatus($request);
    
}