<?php

namespace Modules\Property\Repositories\Backend;

use DB, Mail, Session, DataTables, config;
use Carbon\Carbon;
use Modules\Property\Entities\Property;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\Property\Entities\PropertyAmenities as PropertyAmenities;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepository;
use Modules\Notifications\Repositories\NotificationRepository as NotificationRepository;

class PropertyRepository implements PropertyRepositoryInterface
{

    public $Property;
    protected $model = 'Property';

    function __construct(Property $Property, PropertyAmenities $PropertyAmenities, EmailNotificationsRepository $EmailNotificationsRepository, NotificationRepository $NotificationRepository)
    {
        $this->Property = $Property;
        $this->PropertyAmenities = $PropertyAmenities;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->NotificationRepository = $NotificationRepository;
    }


    public function getRecordBySlug($slug)
    {
        $record =  $this->Property->with(['propertyAmenities', 'propertyRooms', 'propertyPaymentInfo'])->where('slug', $slug)->first();
        if ($record) {
            $record['amenities_id'] = json_decode($record['amenities_ids']);
            $rooms = [];
            if (!empty($record->propertyRooms)) {

                foreach ($record->propertyRooms as $key => $propRoom) {
                    $rooms['room_type'][] = $propRoom->room_type;
                    $record[$propRoom->room_type] = array_filter(
                        array(
                            'is_ac' => $propRoom->is_ac,
                            'is_non_ac' => $propRoom->is_non_ac,
                            'ac_total_seats' => $propRoom->ac_total_seats,
                            'ac_rented_seats' => $propRoom->ac_rented_seats,
                            'ac_amount' => $propRoom->ac_amount,
                            'non_ac_total_seats' => $propRoom->non_ac_total_seats,
                            'non_ac_rented_seats' => $propRoom->non_ac_rented_seats,
                            'non_ac_amount' => $propRoom->non_ac_amount,
                            'ac_is_food_included' => $propRoom->ac_is_food_included,
                            'non_ac_is_food_included' => $propRoom->non_ac_is_food_included,
                        )
                    );
                }
                if (!empty($rooms['room_type'])) {
                    $record['room_type'] = $rooms['room_type'];
                }
            }
        }
        return $record;
    }





    public function getRecord($id)
    {
        return $this->Property->find($id);
    }

    public function getAllRecords($request)
    {
        // OLERJBAL0174
        $properties = $this->Property->whereHas('author', function (Builder $q) {
        });
        if ($request->get('status')) {
            if ($request->get('status') == 'featured') {
                $properties = $properties->where('properties.featured_property', true);
            } else {
                $properties = $properties->where('properties.status', '=', $request->get('status'));
            }
        }

        if ($request->get('strid')) {
            $properties = $properties->where('properties.property_type_id', '=', $request->get('strid'));
        }

        if ($request->get('city')) {
            $properties = $properties->whereHas('city', function (Builder $q) use ($request){
                $q->where('name','LIKE', '%' .  $request->get('city')  . '%');
             });
        }
        $keyword  = $request->get('search');

        if ($request->get('from') && $request->get('to')) {
            $start_date = date('Y-m-d', strtotime($request->get('from')));
            $end_date = date('Y-m-d', strtotime($request->get('to')));
            $properties = $properties->where([['start_date', '<=', $start_date], ['end_date', '>=', $end_date]]);
        }

        if (!empty($keyword)) {
            $properties = $this->searchLike($properties, $keyword);
        }
        if ($request->get('property_code')) {

            $properties = $properties->where('property_code', 'LIKE', '%' . $request->get('property_code') . '%');
        }
        return $properties->orderBy('id', 'desc')->paginate(config::get('custom.default_pagination'));
    }
    public function getUserRecord($request, $id)
    {
        $properties = $this->Property->where('user_id', $id);
        $status = NULL;
        if ($request->get('status')) {
            $properties = $properties->where('properties.status', '=', $request->get('status'));
        }
        if ($request->get('strid')) {
            $properties = $properties->where('properties.property_type_id', '=', $request->get('strid'));
        }
        $keyword  = $request->get('search');
        $to  = $request->get('to');
        $from  = $request->get('from');
        /* if($request->get('from') && $request->get('to')){
            $start_date = date('Y-m-d', strtotime($request->get('from')));
            $end_date = date('Y-m-d', strtotime($request->get('to')));
			
            $properties = $properties->where([['start_date','<=',$start_date],['end_date','>=',$end_date]]);
             
        } */
        if (!empty($keyword)) {
            $properties = $this->searchLike($properties, $keyword);
        }
        return $properties->orderBy('id', 'desc')->paginate(config::get('custom.default_pagination'));
    }

    public function showProperty($slug)
    {
        $record = $this->Property->with('propertyAmenities')->where('slug', $slug)->first();
        return $record;
    }

    /**
     * search by keywork using like
     * @return search result
     */
    public function searchLike($q, $query)
    {
        return $q->where('properties.property_name', 'like', "%{$query}%");
    }

    public function changeStatus($request)
    {
        try {
            $property = $this->getRecord($request->get('id'));
            if ($property) {
                $status_type = '';
                if ($request->get('statustype') == 'status_selfie') {
                    $update_arr = array('status_selfie' => $request->get('status'), 'status_selfie_date' => Carbon::now());
                    $status_type = 'Selfie Status';
                } elseif ($request->get('statustype') == 'status_agreement') {
                    $update_arr = array('status_agreement' => $request->get('status'), 'status_agreement_date' => Carbon::now());
                    $status_type = 'Agreement Status';
                } else {
                    $status_type = 'Approval Status';
                    $update_arr = array('status' => $request->get('status'));
                }
                $status_html = '';
                $message = 'Property status updated successfully';
                $property->update($update_arr);
                if ($request->get('status') == 'publish' || $request->get('status') == 'approved') {
                    $status_html = '<span class="label btext-publish">Publish</span>';
                    $message = 'Property status updated successfully';
                }
                if ($request->get('status') == 'reject' || $request->get('status') == 'rejected') {
                    $message = 'Property rejected successfully';
                    $response['declined'] = 'statusbox' . $property->id;
                }

                $this->EmailNotificationsRepository->sendVendorSelfiAgreementPropertyStatusEmail($property, $status_type, $request->status, Carbon::now());

                $response['message'] = $message;
                $response['type'] = 'success';
                $response['status_code'] = 200;
                $response['id'] = $property->id;
                if ($request->get('status') != 'declined') {
                    $response['statusHtml'] = $status_html;
                }
            } else {
                $response['message'] = trans('flash.error.reocrd_not_available_now');
                $response['type'] = 'error';
                $response['status_code'] = 400;
            }
        } catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function savePropertyPictureMedia($request, $user_id)
    {
        $filename = uploadOnS3Bucket($request->file('files'), '/property/' . $user_id . '/');
        $response['status'] = true;
        $response['status_code'] = 200;

        $response['type'] = 'success';
        $response['message'] = 'File uploaded successfully';
        $response['filename'] = $filename;
        return $response;
    }

    public function updateFeaturedProperty($id, $value)
    {
        $property = $this->getRecord($id);

        if ($property->featured_property == 0) {
            $this->Property->where('id', $id)->update(['featured_property' => $value]);
            $response['message'] = 'Property publish successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
        } else {
            $this->Property->where('id', $id)->update(['featured_property' => $value]);
            $response['message'] = 'Property is not featured';
            $response['type'] = 'success';
            $response['status_code'] = 200;
        }
        return $response;
    }

    public function updateDealoftheDay($id, $value)
    {
        $property = $this->getRecord($id);

        if ($property->deal_of_the_day == 0) {
            $this->Property->where('id', $id)->update(['deal_of_the_day' => $value]);
            $response['message'] = 'Deal of the day published';
            $response['type'] = 'success';
            $response['status_code'] = 200;
        } else {
            $this->Property->where('id', $id)->update(['deal_of_the_day' => $value]);
            $response['message'] = 'Deal is not pushlished';
            $response['type'] = 'success';
            $response['status_code'] = 200;
        }
        return $response;
    }
}
