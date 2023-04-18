<?php

namespace Modules\Notifications\Repositories;


interface NotificationRepositoryInterface
{
    public function getRecord($id);
    
    public function loadNotification($request);

    public function markAsReadNotification($request);

    public function markReadAllNotification($request);
}