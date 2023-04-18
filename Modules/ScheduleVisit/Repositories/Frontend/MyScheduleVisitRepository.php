<?php

namespace Modules\ScheduleVisit\Repositories\Frontend;

use Modules\Property\Entities\Property;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;
use DB,
    Mail,
    Session,
    View;
use DataTables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MyScheduleVisitRepository implements MyScheduleVisitRepositoryInterface {

    protected $model = 'ScheduleVisit';

    function __construct(ScheduleVisit $ScheduleVisit, ScheduleVisitProperty $ScheduleVisitProperty) {
        $this->ScheduleVisit = $ScheduleVisit;
        $this->ScheduleVisitProperty = $ScheduleVisitProperty;
    }

    public function storeScheduleVisit($request) {
        $userid = auth()->user()->id;
        if (!auth()->user()->hasRole('customer')) {
            $response['message'] = trans('flash.error.please_login_with_customer');
            $response['type'] = 'error';
            return $response;
        } else {
            $checkRec = $this->ScheduleVisit->where('user_id', auth()->user()->id)->where('status', 'request')->orderBy('id', 'desc')->first();
            $property = Property::where('id', $request->property_id)->first();
            if ($checkRec) {
                $check = $checkRec->scheduleVisitProperty->count();
                if (!empty($checkRec->payment_id) && $checkRec->payment_id != null) {
                    $response['message'] = 'Your payment already done';
                    $response['type'] = 'error';
                    $response['store-visit'] = 1;
                    $response['redirect-url'] = route('schedulevisit.success', $checkRec->slug);
                    return $response;
                } else if ($check == 0) {
                    $checkRec->schedule_booking_data = json_encode($request->all());
                    $checkRec->save();
                    $addvisit['user_id'] = $checkRec->user_id;
                    $addvisit['schedule_visits_id'] = $checkRec->id;
                    $addvisit['property_id'] = $request->property_id;
                    $this->ScheduleVisitProperty->create($addvisit);
                    $response['message'] = 'Property scheduled for visit.';
                    $response['type'] = 'success';
                    $response['store-visit'] = 1;
                    $response['redirect-url'] = route('schedulevisit.index', $checkRec->slug);
                    return $response;
                } else if ($check < 3) {
                    $chkprop = $this->ScheduleVisitProperty->where('schedule_visits_id', $checkRec->id)->where('property_id', $request->property_id)->first();
                    if (empty($chkprop)) {
                        $checkRec->schedule_booking_data = json_encode($request->all());
                        $checkRec->save();
                        $addvisit['user_id'] = $property->user_id;
                        $addvisit['schedule_visits_id'] = $checkRec->id;
                        $addvisit['property_id'] = $request->property_id;
                        $this->ScheduleVisitProperty->create($addvisit);
                        $response['message'] = 'Property visit scheduled successfuly';
                        $response['type'] = 'success';
                        $response['store-visit'] = 1;
                        $response['redirect-url'] = route('schedulevisit.index', $checkRec->slug);
                        return $response;
                    } else {
                        $response['message'] = 'Property already added, select another property';
                        $response['type'] = 'error';
                        $response['store-visit'] = 1;
                        $response['redirect-url'] = route('schedulevisit.index', $checkRec->slug);
                        // $response['redirect-url'] = route('search') . '?property_type=2';
                        return $response;
                    }
                } else {
                    $response['message'] = 'Cannot add more than three property';
                    $response['type'] = 'error';
                    $response['store-visit'] = 1;
                    $response['redirect-url'] = route('schedulevisit.index', $checkRec->slug);
                    return $response;
                }
            } else {
                $insert['user_id'] = $userid;
                $insert['status'] = 'request';
                $insert['schedule_booking_data'] = json_encode($request->all());
                //$insert['vendor_id'] = $request->user_id;
                $add = $this->ScheduleVisit->create($insert);
                if ($add) {
                    $bookingCode = str_pad($add->id, 4, '0', STR_PAD_LEFT);
                    $add->schedule_code = 'SCV' . $bookingCode;
                    $add->save();
                }
                $addvisit['user_id'] = $property->user_id;
                ;
                $addvisit['schedule_visits_id'] = $add->id;
                $addvisit['property_id'] = $request->property_id;
                $booking = $this->ScheduleVisitProperty->create($addvisit);
                $response['message'] = 'Property added successfuly for schedule visit';
                $response['type'] = 'success';
                $response['store-visit'] = 1;
                $response['redirect-url'] = route('schedulevisit.index', $add->slug);
                return $response;
            }
        }
    }

    public function updateScheduleVisit($request) {
        $request->all();
        $scheduleVisit = $this->ScheduleVisit->where('id', $request->schedule_visits_id)->first();
        if ($scheduleVisit->TotalProperty == 0) {
            $response['message'] = 'Please need to add at least 1 property for visit schedule';
            $response['type'] = 'error';
            $response['store-visit'] = 1;
            $response['redirect-url'] = route('schedulevisit.index', $scheduleVisit->slug);
            return $response;
        } else {
            if (setting_item('schedule_visit_amount') == 0 || setting_item('schedule_visit_amount') == NULL) {
                $response['message'] = 'Please contact to customer support schedule visit charges not set by admin';
                $response['type'] = 'error';
                return $response;
            }
            $scheduleVisit->total = setting_item('schedule_visit_amount');
            $scheduleVisit->save();
            if ($scheduleVisit->schedule_code == NULL) {
                $scheduleVisit->schedule_code = 'SCV' . str_pad($scheduleVisit->id, 4, '0', STR_PAD_LEFT);
                $scheduleVisit->save();
            }
            if (count($request->get('visit')) > 3) {
                $response['message'] = 'You cannot add more than three property in single schedule';
                $response['type'] = 'error';
                return $response;
            }
            if (count($request->get('visit')) > 0) {
                foreach ($request->get('visit') as $proKey => $datetime) {
                    
                    $visit_date_time = Carbon::parse($datetime['date'] . ' ' . $datetime['time']);
                    if ($visit_date_time < now()) {
                        $response['message'] = 'Visit date and time should be greater than current time.';
                        $response['type'] = 'error';
                        return $response;
                    }
                    $update_arr = array('visit_date' => $datetime['date'], 'visit_time' => $datetime['time'], 'visit_date_time' => $visit_date_time);
                    $schduleProperty = $scheduleVisit->scheduleVisitProperty->where('id', $proKey)->first();
                    if ($schduleProperty) {
                        $schduleProperty->update($update_arr);
                    }
                }
            }
            $response['type'] = 'success';
            $response['tab-active'] = 'pills-payment-tab';
            $response['tab-hide'] = 'pills-home';
            return $response;
        }
    }

    public function deleteVisit($request) {
        $this->ScheduleVisitProperty->where('id', $request->visit_id)->delete();
        $response['message'] = 'Property removed successfuly';
        $response['type'] = 'success';
        $response['reload'] = 'true';
        return $response;
    }

    public function getScheduledProperty($slug) {
        return $this->ScheduleVisit->where('slug', $slug)->with(['payment', 'scheduleVisitProperty'])->first();
    }

}
