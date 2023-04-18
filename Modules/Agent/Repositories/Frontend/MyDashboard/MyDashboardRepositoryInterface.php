<?php

namespace Modules\Agent\Repositories\Frontend\MyDashboard;

interface MyDashboardRepositoryInterface
{
  public function updateUserProfileDetails($request);
  
  public function deactivateAccount($request);
}
