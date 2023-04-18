<?php

namespace Modules\Notifications\Repositories;

use DB, Mail, Session, DataTables, config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Notifications\Entities\Notifications;
use Modules\Notifications\Repositories\NotificationRepositoryInterface;

class NotificationRepository implements NotificationRepositoryInterface
{

    function __construct(Notifications $Notifications)
    {
        $this->Notification = $Notifications;
    }

    public function getRecord($id)
    {
        return $this->Notification->find($id);
    }

    public function loadNotification($request)
    {
        $type = $request->get('type');
        $query  = $this->Notification;

        if ($type == 'unread') {
            $query =  $query->where('read_at', null);
        }
        if ($type == 'read') {
            $query =  $query->where('read_at', '!=', null);
        }
        $reporting = $query->where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(config::get('custom.default_pagination'));
        return $reporting;
    }

    public function addNotification($data)
    {
        if (empty($data['data'])) {
            return false;
        }
        $filleable['user_id'] = @$data['user_id'];
        $filleable['data'] = (!empty($data['data'])) ? $data['data'] : [];
        //$filleable['data'] = (!empty($data['data'])) ? json_encode($data['data']) : [];
        $filleable['create_user'] = (auth()->user()) ? Auth::id() : NULL;

        $this->Notification->fill($filleable);
        if ($this->Notification->save()) {
            return true;
        }
    }

    public function addMultipleNotifications($data)
    {
        if ($this->Notification->insert($data)) {
            return true;
        }
    }

    public function markAsReadNotification($id = '')
    {
        if (!empty($id)) {
            Notifications::find($id)->update([
                'read_at' => now()
            ]);
        }
        return response()->json(['msg' => 'success'], 200);
    }

    public function markReadAllNotification($data)
    {
        $notify = Notifications::query();
        $notify->where('read_at', null)->where('user_id', Auth::id())
            ->update([
                'read_at' => now()
            ]);
        return response()->json([], 200);
    }

    public function addRealTimeNotifications($data)
    {
        if (empty($data['data'])) {
            return false;
        }
        if(isset($data['type']) && $data['type'] !=''){
           $filleable['type_id'] = @$data['type_id'];
           $filleable['type'] = @$data['type'];
        }
        $filleable['user_id'] = @$data['user_id'];
        $filleable['data'] = (!empty($data['data'])) ? $data['data'] : [];
        $filleable['create_user'] = (auth()->user()) ? Auth::id() : NULL;


        if(Notifications::create($filleable)) {
            return true;
        }
    }
}
