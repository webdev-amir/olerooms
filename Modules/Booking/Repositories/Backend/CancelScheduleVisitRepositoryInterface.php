<?php

namespace Modules\Booking\Repositories\Backend;


interface CancelScheduleVisitRepositoryInterface
{
    public function getRecord($id);

    public function getAllRecords($request);

    public function getAjaxData($request);

    public function changeStatus($request,$slug);

    public function getVendors();
}
