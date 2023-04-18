<?php

namespace Modules\Review\Repositories\Frontend;

use Carbon\Carbon;
use Modules\Property\Entities\Property;
use Modules\Review\Entities\Review;
use DB, Mail, Session, View;
use Modules\Booking\Entities\Booking;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;

class MyReviewRepository implements MyReviewRepositoryInterface
{
    protected $model = 'Review';

    function __construct(Property $Property, Review $Review, EmailNotificationsRepo $EmailNotificationsRepo)
    {
        $this->Property = $Property;
        $this->Review = $Review;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
    }

    public function getPropertyReviewDetails($request)
    {
        $result = $this->Review->where(['object_id' => $request->property_id, 'booking_id' => $request->booking_id, 'user_id' => auth()->id()])->first();
        return $result;
    }

    public function addUpdateReviewDetails($request)
    {
        try {
            $reviewAdd =  new $this->Review;
            if ($request->id) {
                $reviewAdd =  $this->Review->find($request->id);
            }
            $filleable = $request->only('user_id', 'object_id', 'rate_number', 'content', 'booking_id');
            $filleable['object_model'] = 'property';
            $filleable['user_id'] = auth()->id();
            $filleable['author_ip'] = request()->ip();
            $filleable['publish_date'] = Carbon::now();
            $filleable['status'] = 'publish';
            $reviewAdd->fill($filleable);
            $reviewAdd->save();

            $booking = Booking::find($request->booking_id);
            if ($booking) {
                $Property = Property::where('id',$request->object_id)->withTrashed()->first();
                $Property->rating_avg =  $Property->getRatingAverageAttribute();
                $Property->save();
            }
            $this->EmailNotificationsRepo->sendReviewNotificationMailVendor($booking);
            $this->EmailNotificationsRepo->sendReviewMailAdmin($booking);

            $response['message'] = 'Review submitted successfully';
            $response['type'] = 'success';
            $response['modelClose'] = 'reviewProperty';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
            $response['modelClose'] = 'reviewProperty';
        }
        return $response;
    }

    public function updateReviewReplyVendor($request, $id)
    {
        try {
            $reviewReply =  $this->Review->find($id);
            $filleable = $request->only('reply_content');
            $filleable['replied_at'] = Carbon::now();
            $reviewReply->fill($filleable);
            $reviewReply->save();
            $response['message'] = 'Review reply submitted successfully';
            $response['type'] = 'success';
            $response['modelClose'] = 'reviewProperty';
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
            $response['modelClose'] = 'reviewProperty';
        }
    }
}
