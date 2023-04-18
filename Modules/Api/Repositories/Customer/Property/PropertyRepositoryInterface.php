<?php

namespace Modules\Api\Repositories\Customer\Property;

interface PropertyRepositoryInterface{
   
   public function addFavorite($request);

   public function myWishlist($request);

   public function propertyDetail($request);

   public function propertyReviewListing($request);

   public function scheduleGetOrderId($request);

   public function makePayment($request);

   

  
}

