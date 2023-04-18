<?php

namespace Modules\Api\Repositories\Customer\Notification;

use Modules\Notifications\Entities\Notifications;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $Notifications;
    function __construct(Notifications $Notifications)
    {

        $this->Notifications = $Notifications;
    }
    public function notificationList($request)
    {
        try {
            $notifications = $this->Notifications->where('user_id', auth()->user()->id)->latest()->paginate(10);
            $response = paginationFormat($notifications);
            if (count($notifications) > 0) {
                $response['status_code'] = 200;
                $response['message'] = 'Notification list';
                $response['notification'] = array();
                foreach ($notifications as $key => $notification) {
                    $data = json_decode($notification->data);
                    if(!empty($data->notification)){
                        if($notification->type=='booking'){
                            $booking = $notification->getBookingInfo;
                            $response['notification'][$key]['booking_code'] = !empty($booking) ?$booking->code : '';
                             $response['notification'][$key]['property_cover_image'] = !empty($booking->property) ?$booking->property->CoverImg : '';
                             $response['notification'][$key]['type'] = $notification->type;
                          $response['notification'][$key]['slug'] = isset($notification->type_id) ? $notification->type_id : ''; 
                        }
                        if($notification->type=='scheduling'){
                            $scheduling = $notification->getScheduleInfo;
                            
                            $response['notification'][$key]['schedule_code'] = !empty($scheduling) ?$scheduling->schedule_code : '';
                            $response['notification'][$key]['type'] = $notification->type;
                          $response['notification'][$key]['id'] = isset($notification->type_id) ? $notification->type_id : '';
                          
                          
                        }
                        $notifyData = $data->notification;
                        $response['notification'][$key]['link'] = isset($notifyData->link) ? $notifyData->link : '';
                        $response['notification'][$key]['message'] = isset($notifyData->message) ? $notifyData->message : '';
                        $response['notification'][$key]['image'] = isset($notifyData->image) ? $notifyData->image : '';
                        $response['notification'][$key]['created_date'] = isset($notification->created_date) ? $notification->created_date : '';
                    }
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'no notification found';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }
}