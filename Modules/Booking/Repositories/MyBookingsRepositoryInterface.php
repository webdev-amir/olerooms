<?php

namespace Modules\Booking\Repositories;

interface MyBookingsRepositoryInterface
{
  public function getMyPaymentsHistory($request);

  public function submitUserProfileVerificationData($request);
  
  public function updateUserProfileDetails($request);
  
  public function deactivateAccount($request);
}