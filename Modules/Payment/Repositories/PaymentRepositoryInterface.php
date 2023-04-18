<?php

namespace Modules\Payment\Repositories;


interface PaymentRepositoryInterface
{
    public function getRecord($id);
    
    public function getRecordBySlug($slug);

    public function getAjaxData($request);

    public function getAllRecordsWithFilter($request);

    public function getPaymentStatisticBlockData($request);
}