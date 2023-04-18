<?php

namespace Modules\Booking\Repositories\Backend;


interface BookingRepositoryInterface
{
    public function getRecord($id);

    public function getAllRecords($request);

    public function getHostCommisionHtml($slug);

    public function settHostCommision($slug,$request);

    public function getAjaxData($request);

    public function changeStatus($request,$slug);

    public function getVendors();
}
