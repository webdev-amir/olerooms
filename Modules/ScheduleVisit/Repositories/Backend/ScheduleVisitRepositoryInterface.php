<?php

namespace Modules\ScheduleVisit\Repositories\Backend;


interface ScheduleVisitRepositoryInterface
{
    public function getRecord($id);

    public function getAllRecords($request);

    public function visitDetailsRecord($slug);
}
