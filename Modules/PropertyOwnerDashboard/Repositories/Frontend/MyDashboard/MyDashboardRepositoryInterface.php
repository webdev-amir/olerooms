<?php

namespace Modules\PropertyOwnerDashboard\Repositories\Frontend\MyDashboard;

interface MyDashboardRepositoryInterface
{
  public function getMyPaymentsEarningHistory($request);

  public function submitUserProfileVerificationData($request);
  
  public function updateUserProfileDetails($request);
  
  public function deactivateAccount($request);

  public function acceptBookingRequest($bookingid);
  
  public function rejectBookingRequest($bookingid);
}