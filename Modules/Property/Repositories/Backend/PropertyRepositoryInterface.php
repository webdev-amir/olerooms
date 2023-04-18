<?php

namespace Modules\Property\Repositories\Backend;


interface PropertyRepositoryInterface
{
    public function getAllRecords($request);
    public function changeStatus($request);
    public function showProperty($slug);
}