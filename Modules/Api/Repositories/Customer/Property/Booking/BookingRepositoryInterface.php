<?php

namespace Modules\Api\Repositories\Customer\Property\Booking;


interface BookingRepositoryInterface
{
   public function cancelBooking($request);

   public function bookingPropertyDetail($request);
   
   public function getBookingList($request);

   public function reviewBooking($request);

   public function  deleteBooking($request);

   
   
  
   
       
         
}

