<?php

namespace Modules\ScheduleVisit\Repositories\Frontend;


interface MyScheduleVisitRepositoryInterface
{
    public function storeScheduleVisit($request);

    public function updateScheduleVisit($request);
    public function deleteVisit($request);
    
    public function getScheduledProperty($slug);
    
}