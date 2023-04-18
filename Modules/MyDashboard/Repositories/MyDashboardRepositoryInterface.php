<?php

namespace Modules\MyDashboard\Repositories;

interface MyDashboardRepositoryInterface
{
  public function getMyPaymentsHistory($request);

  public function cancellBookingRequest($request);
  
  public function cancellVisitRequest($request);
}