<?php

namespace Modules\Api\Repositories\Customer\Profile;


interface ProfileRepositoryInterface
{
   public function getCustomerProfileDetails($request);

   public function updateUserProfileDetailsWithOTP($request);

   public function updateUserProfileDetails($request);
   
   public function updateCustomerPhoneNumber($request);

   public function accountDelete($request);

   public function changePassword($request);
   
   public function getSignedURL($request);
}

