<?php

namespace Modules\Booking\Repositories\Backend;


interface CancelBookingRepositoryInterface
{
    public function getRecord($id);

    public function getAjaxData($request);

    public function changeStatus($request,$slug);

    public function getVendors();
}
