<?php

namespace Modules\Api\Repositories\Customer\Property\Schedule;

use Validator;
use  Modules\Api\Repositories\Customer\Property\Schedule\ScheduleRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Modules\Review\Entities\Review;
use Modules\Payment\Entities\Payment;
use Modules\Property\Entities\Property;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Users\Entities\UserWishlist;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;
use Modules\Booking\Entities\Booking;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;
use Modules\Payment\Repositories\Frontend\RozarPay\RozarPayPaymentRepositoryInterface as RozarPayPaymentRepository;

class ScheduleRepository implements ScheduleRepositoryInterface
{

    function __construct(User $User, RozarPayPaymentRepository $RozarPayPaymentRepository, Property $Property, Review $Review, PropertyType $PropertyType, UserWishlist $Userwishlist, ScheduleVisit $ScheduleVisit, ScheduleVisitProperty $ScheduleVisitProperty, EmailNotificationsRepository $EmailNotificationsRepository, Booking $Booking)
    {
        $this->User = $User;
        $this->Property = $Property;
        $this->PropertyType = $PropertyType;
        $this->Userwishlist = $Userwishlist;
        $this->Review = $Review;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->ScheduleVisitProperty = $ScheduleVisitProperty;
        $this->Booking = $Booking;
        $this->paymentClass = Payment::class;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->RozarPayPaymentRepository = $RozarPayPaymentRepository;
    }

    public function addPropertySchedule($request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'customer_name' => 'required',
            'customer_email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $property = $this->Property->with('propertyType')->where('id', $request->property_id)->first();
            if ($property) {
                if ($property->property_type_id == 2) {
                    $checkRec = $this->ScheduleVisit->where('user_id', auth()->user()->id)
                        ->where('status', 'request')->orderBy('id', 'desc')->first();
                    $visit_date_time = Carbon::parse($request->date . ' ' . $request->time);
                    if ($visit_date_time < now()) {
                        $response['message'] = 'Visit date and time should be greater than current time.';
                        $response['status_code'] = 200;
                        return $response;
                    }
                    if ($checkRec) {
                        $check = $checkRec->scheduleVisitProperty->count();


                        if ($check > 2) {

                            $response['status_code'] = 200;
                            $response['message'] = 'you can not add more then three property';
                            $response['slug'] = $checkRec->slug;
                        } else {
                            $chkprop = $this->ScheduleVisitProperty->where('schedule_visits_id', $checkRec->id)->where('property_id', $request->property_id)->first();
                            if (empty($chkprop)) {
                                $hasRecord = $this->ScheduleVisitProperty->where('schedule_visits_id', $checkRec->id)->where('property_id', $request->property_id)->first();
                                $scheduleProperty['schedule_visits_id'] = $checkRec->id;
                                $scheduleProperty['user_id'] = auth()->user()->id;
                                $scheduleProperty['property_id'] = $request->property_id;
                                $scheduleProperty['visit_date'] = $request->date;
                                $scheduleProperty['visit_time'] = $request->time;
                                $scheduleProperty['visit_date_time'] = $visit_date_time;
                                $propertySave = $this->ScheduleVisitProperty->create($scheduleProperty);
                                $schedule_visit_id = $propertySave->schedule_visits_id;
                                $response['status_code'] = 200;
                                $response['message'] = 'Property schedule sucessfully';
                                $response['data'] = array("slug" => $checkRec->slug);
                            } else {
                                $response['status_code'] = 200;
                                $response['message'] = 'Property already added, select another property';
                            }
                        }
                    } else {
                        $schedule['user_id'] = auth()->user()->id;
                        $schedule['status'] = 'request';
                        $array = array("property_id" => $request->property_id);
                        $schedule['schedule_booking_data'] = json_encode($array);
                        $add = $this->ScheduleVisit->create($schedule);
                        if ($add) {
                            $bookingCode = str_pad($add->id, 4, '0', STR_PAD_LEFT);
                            $add->schedule_code = 'SCV' . $bookingCode;
                            $add->save();
                        }
                        $scheduleProperty['schedule_visits_id'] = $add->id;
                        $scheduleProperty['user_id'] = auth()->user()->id;
                        $scheduleProperty['property_id'] = $request->property_id;
                        $scheduleProperty['visit_date'] = $request->date;
                        $scheduleProperty['visit_time'] = $request->time;
                        $scheduleProperty['visit_date_time'] = $visit_date_time;
                        $propertySave = $this->ScheduleVisitProperty->create($scheduleProperty);
                        $response['status_code'] = 200;
                        $response['message'] = 'property schedule sucessfully';

                        $response['data'] = array("slug" => $add->slug);
                    }
                } else {
                    $response['status_code'] = 200;
                    $response['message'] = 'property type not matched(only flat)';
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'property not available';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }


    public function updatePropertySchedule($request)
    {

        $scheduleVisit = $this->ScheduleVisit->where('id', $request->schedule_visit_id)->first();
        if ($scheduleVisit->TotalProperty == 0) {
            $response['message'] = 'Please need to add at least 1 property for visit schedule';
            $response['status_code'] = 402;
        } else {
            if (setting_item('schedule_visit_amount') == 0 || setting_item('schedule_visit_amount') == NULL) {
                $response['message'] = 'Please contact to customer support schedule visit charges not set by admin';
                $response['status_code'] = 402;
                // $response['type'] = 'error';
            } else {
                $scheduleVisit->total = setting_item('schedule_visit_amount');
                $scheduleVisit->save();
                if ($scheduleVisit->schedule_code == NULL) {
                    $scheduleVisit->schedule_code = 'SCV' . str_pad($scheduleVisit->id, 4, '0', STR_PAD_LEFT);
                    $scheduleVisit->schedule_booking_data = json_encode($request->all());
                    $scheduleVisit->save();
                }
                if (count($request->visit) > 3) {
                    $response['message'] = 'You cannot add more than three property in single schedule';
                    $response['type'] = 'error';
                } elseif (count($request->visit) > 0) {
                    foreach ($request->visit as $key => $val) {

                        $visit_date_time = Carbon::parse($val['date'] . ' ' . $val['time']);
                        if ($visit_date_time < now()) {
                            $response['message'] = 'Visit date and time should be greater than current time.';
                            $response['status_code'] = 402;
                            return response()->json($response, $response['status_code'])
                                ->withHeaders(checkVersionStatus(
                                    $request->headers->get('Platform'),
                                    $request->headers->get('Version')
                                ))->setStatusCode($response['status_code']);
                        }
                        $update_arr = array('visit_date' => $val['date'], 'visit_time' => $val['time'], 'visit_date_time' => $visit_date_time);
                        $schduleProperty = $scheduleVisit->scheduleVisitProperty->where('id', $val['id'])->first();
                        if ($schduleProperty) {
                            $schduleProperty->update($update_arr);
                        }
                    }
                    $order_arr = [
                        // $response['order_data'] =
                        //     [
                        'bookingtype' => 'ScheduleVisit',
                        'amount' => setting_item('schedule_visit_amount'),
                        'request_id' => $request->schedule_visit_id,
                    ];
                    $response['order_data'] = $this->RozarPayPaymentRepository->orderIdGenerate((object)$order_arr);
                    $response['message'] = 'ScheduleVisit added successfully.';
                    $response['status_code'] = 200;
                }
            }
        }

        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }


    public function getScheduleProperty($request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_slug' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $scheduleVisit =  $this->ScheduleVisit->where('slug', $request->schedule_slug)
                ->with(['payment', 'scheduleVisitProperty'])->first();

            if ($scheduleVisit) {
                $response['status_code'] = 200;
                $response['message'] = 'properties data';
                $response['data']['id'] = $scheduleVisit->id;
                $response['data']['personal_detail']['name'] = auth()->user()->name;
                $response['data']['personal_detail']['email'] = auth()->user()->email;
                $response['data']['personal_detail']['phone_no'] = auth()->user()->phone;
                $response['data']['payment_detail']['payable_amount'] = numberformatWithCurrency(setting_item('schedule_visit_amount'));

                if (count($scheduleVisit->scheduleVisitProperty) > 0) {

                    foreach ($scheduleVisit->scheduleVisitProperty as $key => $val) {

                        if (
                            $val->property->author->userCompleteProfileVerifired && $val->property->author->ComponyLogo !=
                            '' && config('custom.is_company_logo_show')
                        ) {
                            $response['data']['property_detail'][$key]['company_logo'] = $val->property->author->ComponyLogo;
                        } else {
                            $response['data']['property_detail'][$key]['company_logo'] = '';
                        }
                        $response['data']['property_detail'][$key]['visit_id'] = $val->id;
                        $response['data']['property_detail'][$key]['id'] = $val->property->id;
                        $response['data']['property_detail'][$key]['slug'] = $val->property->slug;
                        $response['data']['property_detail'][$key]['property_code'] = $val->property->property_code;
                        $response['data']['property_detail'][$key]['city_name'] = $val->property->city->name;
                        $response['data']['property_detail'][$key]['state_name'] = $val->property->state->name;
                        $response['data']['property_detail'][$key]['rating_review'] = $val->property->RatingAverage;
                        $response['data']['property_detail'][$key]['property_image'] = $val->property->CoverImg;

                        if ($val->property->AvailableForType) {
                            $response['data']['property_detail'][$key]['available_for_type']['image'] = url('images/hotel-icon.svg');
                            $response['data']['property_detail'][$key]['available_for_type']['value'] = $val->property->AvailableForType;
                        }
                        if ($val->property->FurnishedTypeValue) {
                            $response['data']['property_detail'][$key]['furnished_type']['image'] = url('images/sleep.svg');
                            $response['data']['property_detail'][$key]['furnished_type']['value'] = $val->property->FurnishedTypeValue;
                        }
                        if ($val->property->total_seats) {
                            $response['data']['property_detail'][$key]['total_seats']['image'] = url('images/seat.svg');
                            $response['data']['property_detail'][$key]['total_seats']['value'] = $val->property->total_seats;
                        }
                        if ($val->property->convenient_time) {
                            $response['data']['property_detail'][$key]['convenient_time'] = $val->property->convenient_time;
                        }

                        $response['data']['property_detail'][$key]['date'] = (!empty($val->visit_date) && $val->visit_date != '0000-00-00') ? $val->visit_date : '';
                        $response['data']['property_detail'][$key]['time'] = (!empty($val->visit_time) && $val->visit_time != '00:00:00') ? $val->visit_time : '';
                        if ($val->property->propertyAmenities) {
                            foreach ($val->property->propertyAmenities as $key2 => $amenity) {
                                $response['data']['property_detail'][$key]['amenities'][$key2]['images'] = $amenity->amenities->PicturePath;
                                $response['data']['property_detail'][$key]['amenities'][$key2]['name'] = $amenity->amenities->name;
                            }
                        }
                    }
                } else {
                    $response['status_code'] = 200;
                    $response['message'] = 'no schedule property this time';
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'schedule code not matched';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function deleteScheduleProperty($request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required',
            'property_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $property = $this->ScheduleVisitProperty->where('property_id', $request->property_id)->where('schedule_visits_id', $request->visit_id)->first();

            if ($property) {
                $property->delete();
                $response['status_code'] = 200;
                $response['message'] = 'Property removed successfuly';
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Property not present in this schedule';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function myVisits($request)
    {
        try {
            $schedules = $this->ScheduleVisit->where('user_id', auth()->user()->id)
                ->whereNotIn('status', ScheduleVisit::notAcceptedStatus)->with('scheduleVisitPropertyWithPropertyDetails')->whereHas('scheduleVisitProperty', function ($query) {
                });
            if ($request->type == "past") {
                $schedules = $schedules->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '<', now()->format("Y-m-d"));
                });
            }
            if ($request->type == "upcoming") {
                $schedules = $schedules->whereHas('scheduleVisitProperty', function ($query) {
                    $query->where("visit_date", '>', now()->format("Y-m-d"));
                });
            }

            // $schedules = $schedules->paginate(10);
            $schedules = $schedules->latest()->paginate(10);
            $response = paginationFormat($schedules);
            $response['status_code'] = 200;
            $response['message'] = 'visit list';
            foreach ($schedules as $key => $schedule) {
                $response['schedule'][$key]['code'] = $schedule->schedule_code;
                $response['schedule'][$key]['id'] = $schedule->id;
                $response['schedule'][$key]['amount'] = $schedule->payment->amount;
                $response['schedule'][$key]['visit_status'] = $schedule->status;
                foreach ($schedule->scheduleVisitPropertyWithPropertyDetails as $keyp => $schedule1) {
                    $response['schedule'][$key]['property'][$keyp]['id'] = $schedule1->property->id;
                    $response['schedule'][$key]['property'][$keyp]['code'] = $schedule1->property->property_code;
                    $response['schedule'][$key]['property'][$keyp]['name'] = $schedule1->property->property_name;
                }
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function visitPropertyDetail($request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $properties = $this->ScheduleVisit->where('id', $request->schedule_id)
                ->with(['scheduleVisitProperty', 'payment', 'customer'])->first();
            if ($properties) {
                $response['status_code'] = 200;
                $response['message'] = 'visit list';

                $response['visit']['cancel_request_date'] = $properties->cancel_request_date;
                $response['visit']['can_send_cancel_request'] = !in_array($properties->status, ['completed', 'rejected']) && $properties->status != 'cancelled' && $properties->CancellationBeforeDate == true && isset($properties->payment) ? true : false;
                $response['visit']['visit_code'] = $properties->schedule_code;
                $response['visit']['visit_status'] = $properties->status;

                foreach ($properties->scheduleVisitProperty as $key => $row) {
                    $response['visit']['amount'] = isset($properties->payment->amount) ? $properties->payment->amount : 'N/A';
                    $response['visit']['property'][$key]['code'] = $row->property->property_code;
                    $response['visit']['property'][$key]['name'] = $row->property->property_name;
                    $response['visit']['property'][$key]['rating'] = $row->property->RatingAverage;
                    $response['visit']['property'][$key]['address'] = $row->property->full_address;
                    $response['visit']['property'][$key]['image'] = $row->property->CoverImg;
                    $response['visit']['property'][$key]['lat'] = $row->property->lat;
                    $response['visit']['property'][$key]['long'] = $row->property->long;
                    $response['visit']['property'][$key]['visit_date'] = $row->visit_date;
                    $response['visit']['property'][$key]['visit_time'] = $row->visit_time;
                    $response['visit']['property'][$key]['property_type'] = $row->property->propertyType->name;
                    $response['visit']['property'][$key]['furnished_type'] = $row->property->furnished_type;
                    $response['visit']['property'][$key]['total_seats'] = $row->property->total_seats;
                    $response['visit']['property'][$key]['owner_convenient_time'] = $row->property->convenient_time;
                    $response['visit']['property'][$key]['owner_convenient_time'] = $row->property->convenient_time;
                    $response['visit']['customer']['name'] = $properties->customer->name;
                    $response['visit']['customer']['email'] = $properties->customer->email;
                    $response['visit']['customer']['image'] = $properties->customer->PicturePath;
                    $response['visit']['transaction']['id'] = isset($properties->payment->transaction_id) ? $properties->payment->transaction_id : 'N/A';
                    $response['visit']['transaction']['amount'] = isset($properties->payment->amount) ? $properties->payment->amount : 'N/A';
                    $response['visit']['transaction']['payment_mode'] = isset($properties->payment->method) ? $properties->payment->method : 'N/A';
                    $response['visit']['transaction']['payment_date'] = isset($properties->payment->created_at) ? $properties->payment->created_at : 'N/A';

                    if ($row->property->propertyAmenities) {
                        foreach ($row->property->propertyAmenities as $key2 => $amenity) {
                            $response['visit']['property'][$key]['amenities'][$key2]['images'] = $amenity->amenities->PicturePath;
                            $response['visit']['property'][$key]['amenities'][$key2]['name'] = $amenity->amenities->name;
                        }
                    }
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'no visit found with this schedulecode';
            }
        } catch (\Exception $th) {
            $response['status_code'] = 402;
            $response['message'] = 'Something went wrong';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function cancelScheduleMyVisit($request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_code' => 'required',
            'cancel_reason' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        $scheduleVisitData = $this->ScheduleVisit->where('schedule_code', $request->schedule_code)->first();
        if ($scheduleVisitData) {
            if ($scheduleVisitData->status == 'cancelled') {
                $response['message'] = "Your Schedule Visit already cancelled!";
                $response['status_code'] = 200;
            } elseif ($scheduleVisitData->CancellationBeforeDate == false) {
                $response['message'] = "Your Schedule Visit already checked-In , Can't be cancelled now";
                $response['status_code'] = 200;
            } elseif ($scheduleVisitData->cancel_request_date) {
                $response['message'] = "Already requested for Schedule Visit cancellation on " . Carbon::parse($scheduleVisitData->cancel_request_date)->format('d M Y');
                $response['status_code'] = 200;
            } else {
                $scheduleVisitData->cancel_request_date = now();
                $scheduleVisitData->cancellation_reason = $request->cancel_reason;
                $scheduleVisitData->schedule_visit_cancelled_reject_date = NULL;
                $scheduleVisitData->save();
                $response['message'] = "Cancel Schedule Visit request submitted successfully!";
                $response['status_code'] = 200;
                $this->EmailNotificationsRepository->sendVisitCancellationRequestEmail(auth()->user(), $scheduleVisitData);
                foreach ($scheduleVisitData->scheduleVisitProperty as $visitProperty) {
                    $this->EmailNotificationsRepository->sendVisitCancellationRequestEmailToVendor(auth()->user(), $scheduleVisitData, $visitProperty);
                }
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'No Schedule Visit data found.';
        }

        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }
}
