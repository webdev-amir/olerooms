<?php

namespace Modules\Review\Repositories\Backend;


interface ReviewRepositoryInterface
{
    public function getRecord($id);

    public function getAjaxData($request);

    public function getVendors();
}
