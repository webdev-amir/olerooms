<?php

namespace Modules\Api\Repositories\Customer\Property\Schedule;


interface ScheduleRepositoryInterface
{
   public function addPropertySchedule($request);

   public function getScheduleProperty($request);

   public function deleteScheduleProperty($request);

   public function  myVisits($request);

   public function visitPropertyDetail($request);
   
   
   
  
   
       
         
}

