<?php

namespace Modules\EmailNotifications\Repositories;


interface EmailNotificationsRepositoryInterface
{

    public function sendSchduleVisitBookingEmail($vendor,$booking);

    public  function sendCreateUserEmail($request, $user);
}