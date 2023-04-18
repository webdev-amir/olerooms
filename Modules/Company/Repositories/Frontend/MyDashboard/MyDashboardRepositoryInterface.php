<?php

namespace Modules\Company\Repositories\Frontend\MyDashboard;

interface MyDashboardRepositoryInterface
{
  public function updateUserProfileDetails($request);
  
  public function deactivateAccount($request);
}