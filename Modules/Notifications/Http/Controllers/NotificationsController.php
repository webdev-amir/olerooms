<?php

namespace Modules\Notifications\Http\Controllers;

use view;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Notifications\Repositories\NotificationRepositoryInterface;

class NotificationsController extends Controller
{

    public function __construct(NotificationRepositoryInterface $NotificationRepositoryInterface)
    {
        $this->middleware(['auth']);
        $this->notificationRepo = $NotificationRepositoryInterface;
    }

    public function index(request $request)
    {
        $rows =  $this->notificationRepo->loadNotification($request);
        $type =  $request->get('type');
        return view('notifications::index', compact('rows', 'type'));
    }

    public function markAsRead($id)
    {
        $response = $this->notificationRepo->markAsReadNotification($id);
        return $response;
    }

    public function markReadAllNotification(Request $request)
    {
        $rows =  $this->notificationRepo->markReadAllNotification($request);
    }
}
