<?php

namespace Modules\Cron\Repositories;

interface CronRepositoryInterface
{
    public function sendTestMailandNotificationCron();

    public function makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor();
    
    public function makeConfirmedToCompletedIfBookingCheckOutDateIsPassed();
}
