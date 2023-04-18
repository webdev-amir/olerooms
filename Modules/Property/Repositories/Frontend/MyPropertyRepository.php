<?php

namespace Modules\Property\Repositories\Frontend;

use Modules\Property\Entities\Property;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Property\Entities\PropertyAmenities;
use Modules\Property\Entities\PropertySessionEntry;
use Modules\Property\Entities\PropertyPaymentInformation;
use Modules\Users\Entities\UserWishlist;
use DB, Mail, Session, View;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Carbon\Carbon;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepo;
use App\Repositories\Frontend\FrontendRepositoryInterface as FrontendRepository;

class MyPropertyRepository implements MyPropertyRepositoryInterface
{

    protected $model = 'Property';
    protected $spaceDateClass;
    protected $userWishListClass;

    function __construct(Property $Property, PropertyType $PropertyType, PropertyPaymentInformation $PropertyPaymentInfo, PropertyAmenities $PropertyAmenities, PropertySessionEntry $PropertySessionEntry, UserWishlist $UserWishlist, EmailNotificationsRepo $EmailNotificationsRepo, FrontendRepository $FrontendRepository)
    {
        $this->Property = $Property;
        $this->PropertyPaymentInfo = $PropertyPaymentInfo;
        $this->PropertyType = $PropertyType;
        $this->PropertyAmenities = $PropertyAmenities;
        $this->PropertySessionEntry = $PropertySessionEntry;
        $this->userWishListClass = $UserWishlist;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
        $this->FrontendRepository = $FrontendRepository;
    }

    public function getRecordBySlug($slug)
    {
        $record =  $this->Property->with(['propertyAmenities', 'propertyAvailableFor', 'propertyRooms', 'propertyPaymentInfo'])->where('slug', $slug)->first();
        if ($record) {
            $record['amenities_id'] = json_decode($record['amenities_ids']);
            $record['available_fors'] = json_decode($record['available_for_names']);
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

    public function getRecordByID($id)
    {
        $record =  $this->Property->with(['propertyAmenities', 'propertyAvailableFor', 'propertyRooms'])->where('id', $id)->first();
        if ($record) {
            $record['amenities_id'] = json_decode($record['amenities_ids']);
            $record['available_fors'] = json_decode($record['available_for_names']);
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
                $record['room_type'] = $rooms['room_type'];
            }
        }
        return $record;
    }

    public function getSimilarProperty($property)
    {
        $record =  $this->Property->where([['id', '!=', $property->id], ['property_type_id', $property->property_type_id], ['is_publish', true,], ['status', 'publish'], ['amount', '!=', 0]])
            // ->where('map_location', 'LIKE', '%' . $property->map_location . '%')
            ->whereBetween('lat', [$property->lat, $property->long])
            ->whereBetween('long', [$property->lat, $property->long])
            ->whereHas('author', function ($query) {
                $query->where('users.status', 1);
            })->whereHas('author.userCompleteProfileVerifiredIfApproved')->orderBy('status', 'asc')->take(8)->get();
        return $record;
    }

    public function getSessionEntryFormData()
    {
        if (session()->has('session_property_entry')) {
            $data = $this->PropertySessionEntry->where('slug', Session::get('session_property_entry'))->first();
            if ($data) {
                return json_decode($data['step_data']);
            }
        }
        return false;
    }

    public function getSessionEntryAllData()
    {
        if (session()->has('session_property_entry')) {
            $data = $this->PropertySessionEntry->where('slug', Session::get('session_property_entry'))->first();
            if ($data) {
                return $data;
            }
        }
        return false;
    }

    public function getSessionEntryAllDataBySlug($slug)
    {
        $data = $this->PropertySessionEntry->where('slug', $slug)->first();
        if ($data) {
            return $data;
        }
        return false;
    }

    public function storePropertyProcessSteps($request)
    {
        $_CURRENT_STEP = (int)$request->get('step');
        $message = 'Property details successfully saved.';
        $_NEXT_STEP = $_CURRENT_STEP + 1;
        $data = [
            'message' => $message,
            'status_code' => 205,
            'type' => 'success',
            'current_step' => 'step_' . $_CURRENT_STEP,
            'next_step' => 'step_' . $_NEXT_STEP,
            'step' => $_NEXT_STEP,
            'scroll' => '150',
        ];
        $stepData['step_' . $_CURRENT_STEP] = $request->all();
        if ($_CURRENT_STEP < 6) {
            $insert['current_step'] = $_CURRENT_STEP + 1;
        }

        if ($request->session()->has('session_property_entry')) {
            $property = $this->PropertySessionEntry->findBySlug(Session::get('session_property_entry'));
            if (!$property) {
                $insert['user_id'] = auth()->user()->id;
                $insert['property_type'] = $request->get('property_type');
                $insert['steps'] = $_CURRENT_STEP;
                $property = $this->PropertySessionEntry->create($insert);
                Session::put('session_property_entry', $property->slug);
            }
        } else {
            $insert['user_id'] = auth()->user()->id;
            $insert['property_type'] = $request->get('property_type');
            $insert['steps'] = $_CURRENT_STEP;
            $property = $this->PropertySessionEntry->create($insert);
            Session::put('session_property_entry', $property->slug);
        }

        if ($property->steps < 2) {
            $insert['steps'] = $_CURRENT_STEP;
        }
        $oldSteps = (array) json_decode($property['step_data']);
        $collection = collect($oldSteps);
        $collection->put('step_' . $_CURRENT_STEP, $request->all());
        $resSteps = $collection->all();
        $insert['step_data'] = json_encode($resSteps);
        $propertyup = $property->update($insert);
        $sessionAllData = $this->getSessionEntryAllData();
        $sessionData =  $this->getSessionEntryFormData();
        $amenitiesData = \Cache::remember('amenitiesData', 300, function () {
            return  $this->FrontendRepository->getamenitiesData();
        });

        if ($sessionData) {
            $property_type = getPropertyTypeBySlug($sessionData->step_1->property_type);
            $data['property_type_name'] = $property_type->name;
        } else {
            $data['property_type_name'] = "";
        }

        if ($_CURRENT_STEP == 1) {
            $insert['property_type'] = $request->get('property_type');
        }
        if ($_CURRENT_STEP == 1) {
            $data['property_type'] = $request->get('property_type');
            $propertyTypeData = PropertyType::where('slug', $request->get('property_type'))->first();
            if ($data['property_type'] == 'flat') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step2includes.flat_step2', $data)->with(compact(['sessionData', 'sessionAllData', 'propertyTypeData']))->render());
            } elseif ($data['property_type'] == 'homestay') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step2includes.homestay_step2', $data)->with(compact(['sessionData', 'sessionAllData', 'propertyTypeData']))->render());
            } elseif ($data['property_type'] == 'guest-hotel') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step2includes.hotel_step2', $data)->with(compact(['sessionData', 'sessionAllData', 'propertyTypeData']))->render());
            } elseif ($data['property_type'] == 'hostel-pg') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step2includes.hostel_pg_step2', $data)->with(compact(['sessionData', 'sessionAllData', 'propertyTypeData']))->render());
            } else {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step2includes.hostel_pg_one_step2', $data)->with(compact(['sessionData', 'sessionAllData', 'propertyTypeData']))->render());
            }
        }

        if ($_CURRENT_STEP == 2) {
            $data['property_type'] = $request->get('property_type');
            if ($data['property_type'] == 'flat') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step3includes.flat_step3', $data)->with(compact(['sessionAllData', 'sessionData', 'amenitiesData']))->render());
            } elseif ($data['property_type'] == 'homestay') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step3includes.homestay_step3', $data)->with(compact(['sessionAllData', 'sessionData', 'amenitiesData']))->render());
            } elseif ($data['property_type'] == 'guest-hotel') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step3includes.hotel_step3', $data)->with(compact(['sessionAllData', 'sessionData', 'amenitiesData']))->render());
            } elseif ($data['property_type'] == 'hostel-pg') {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step3includes.hostel_pg_step3', $data)->with(compact(['sessionAllData', 'sessionData', 'amenitiesData']))->render());
            } else {
                $data['html_data'] = json_encode(View::make('property::frontend.manageProperty.steps.create.step3includes.hostel_pg_one_step3', $data)->with(compact(['sessionAllData', 'sessionData', 'amenitiesData']))->render());
            }
            $data['image_panel'] = true;
            $data['active_rooms'] = json_encode($request->get('room_type'), JSON_FORCE_OBJECT);
        }


        if ($_NEXT_STEP == 5) {
            $data = $this->savePropertyRecord($request, $property->slug);
        }
        return $data;
    }

    public function savePropertyRecord($request, $slug)
    {
        $spaceSession = $this->getSessionEntryAllDataBySlug($slug);
        $stepFirst  = (array) $spaceSession->StepsFirst;
        $stepSecond = (array) $spaceSession->StepsSecond;
        $StepsThird = (array) $spaceSession->StepsThird;
        $StepsFourth = (array) $spaceSession->StepsFourth;
        $stepFirst['status'] = 'pending';
        $stepAlls = $stepFirst + $stepSecond + $StepsThird + $StepsFourth; //include 1,2,3 and 4 Step
        $stepAlls['city_id'] = $stepAlls['city'];
        $stepAlls['area_id'] = $stepAlls['area'];
        $stepAlls['state_id'] = $stepAlls['state'];
        $stepAlls['map_location'] = $stepAlls['location'];
        $stepAlls['full_address'] = $stepAlls['address'];
        $stepAlls['full_address'] = $stepAlls['address'];
        if ($stepAlls['upload_selfie'] != '') {
            $stepAlls['status_selfie'] = 'pending';
        }
        if ($stepAlls['upload_agreement'] != '') {
            $stepAlls['status_agreement'] = 'pending';
        }

        $stepAlls['property_type_id'] = $this->PropertyType->getIdBySlug($stepAlls['property_type']);
        $stepAlls['amenities_ids'] = !empty($stepAlls['amenities_id']) ? json_encode($stepAlls['amenities_id']) : json_encode([]);
        $stepAlls['available_for_names'] = !empty($stepAlls['available_fors']) ? json_encode($stepAlls['available_fors']) : json_encode([]);
        //$stepAlls['available_for'] = NULL;
        try {
            if ($property = $this->Property->create($stepAlls)) {
                //add Search Address
                $areaName = ($property->area) ? $property->area->name : '';
                $cityName = ($property->city) ? $property->city->name : '';
                $stateName = ($property->state) ? $property->state->name : '';
                $search_address = $areaName.', '.$cityName.', '.$stateName;
               
                $property_code_state = $property->state->stateCode ? $property->state->stateCode : strtoupper(substr($property->state->name, 0, 2));
                $property_code_initials = strtoupper(substr($property->property_name, 0, 3));
                $property_code_random_no = str_pad($property->id, 4, '0', STR_PAD_LEFT);
                $property_code = 'OLE' . $property_code_state . $property_code_initials . $property_code_random_no;
                $property->property_code = $property_code;
                $property->search_address = $search_address;
                $property->save();

                $this->savePropertyRooms($property, $stepSecond);
                $this->savePropertyRoomImages($property, $StepsThird);
                $this->savePropertyAmenities($property);
                $this->savePropertyAvailableFor($property);
                $StepsFourth['property_id'] = $property->id;
                $this->PropertyPaymentInfo->create($StepsFourth);
                $data = [
                    'message' => trans('flash.success.property_added_foes_for_admin_approval'),
                    'status_code' => 205,
                    'type' => 'success',
                    'url' => route('manageProperty.success', [$property->slug]),
                ];
                $this->EmailNotificationsRepo->sendAddPropertyEmailForAdmin(auth()->user(), $property);
                if (session()->has('session_property_entry')) {
                    Session::forget('session_property_entry');
                    $this->deleteSessionEntryDataBySlug($slug);
                }
                return $data;
            }
        } catch (\Exception $e) {
            $spaceSession->current_step = $spaceSession->current_step - 1;
            $spaceSession->save();
            $data = [
                'message' => $e->getMessage(),
                'status_code' => 400,
                'type' => 'error'
            ];
            return $data;
        }
        $data = [
            'message' => trans('flash.error.oops_something_went_wrong_creating_record'),
            'status_code' => 400,
            'type' => 'error'
        ];
        return $data;
    }

    public function savePropertyRooms($property, $stepSecond, $update = false)
    {
        $property->propertyRooms()->delete();
        if ($update) {
            foreach ($stepSecond['room_type'] as $k => $room_type) {
                $insert[$k]['property_id'] = $property->id;
                $insert[$k]['room_type'] = $room_type;
                $insert[$k]['is_ac'] =  $stepSecond[$room_type]['is_ac'] ?? 0;
                $insert[$k]['is_non_ac'] = $stepSecond[$room_type]['is_non_ac'] ?? 0;
                $insert[$k]['ac_total_seats'] = $stepSecond[$room_type]['ac_total_seats'] ?? 0;
                $insert[$k]['ac_rented_seats'] = $stepSecond[$room_type]['ac_rented_seats'] ?? 0;
                $insert[$k]['ac_amount'] = $stepSecond[$room_type]['ac_amount'] ?? null;
                $insert[$k]['non_ac_total_seats'] = $stepSecond[$room_type]['non_ac_total_seats'] ?? 0;
                $insert[$k]['non_ac_rented_seats'] = $stepSecond[$room_type]['non_ac_rented_seats'] ?? 0;
                $insert[$k]['non_ac_amount'] = $stepSecond[$room_type]['non_ac_amount'] ?? null;
                $insert[$k]['ac_is_food_included'] = $stepSecond[$room_type]['ac_is_food_included'] ?? 0;
                $insert[$k]['non_ac_is_food_included'] = $stepSecond[$room_type]['non_ac_is_food_included'] ?? 0;
                $insert[$k]['created_at'] = now();
                $insert[$k]['updated_at'] = now();
            }
        } else {
            foreach ($stepSecond['room_type'] as $k => $room_type) {
                $insert[$k]['property_id'] = $property->id;
                $insert[$k]['room_type'] = $room_type;
                $insert[$k]['is_ac'] =  $stepSecond[$room_type]->is_ac ?? 0;
                $insert[$k]['is_non_ac'] = $stepSecond[$room_type]->is_non_ac ?? 0;
                $insert[$k]['ac_total_seats'] = $stepSecond[$room_type]->ac_total_seats ?? null;
                $insert[$k]['ac_rented_seats'] = $stepSecond[$room_type]->ac_rented_seats ?? null;
                $insert[$k]['ac_amount'] = $stepSecond[$room_type]->ac_amount ?? null;
                $insert[$k]['non_ac_total_seats'] = $stepSecond[$room_type]->non_ac_total_seats ?? null;
                $insert[$k]['non_ac_rented_seats'] = $stepSecond[$room_type]->non_ac_rented_seats ?? null;
                $insert[$k]['non_ac_amount'] = $stepSecond[$room_type]->non_ac_amount ?? null;
                $insert[$k]['ac_is_food_included'] = $stepSecond[$room_type]->ac_is_food_included ?? 0;
                $insert[$k]['non_ac_is_food_included'] = $stepSecond[$room_type]->non_ac_is_food_included ?? 0;
                $insert[$k]['created_at'] = now();
                $insert[$k]['updated_at'] = now();
            }
        }
        $ac_amounts = array_column($insert, 'ac_amount');
        $non_ac_amounts = array_column($insert, 'non_ac_amount');
        $amounts =  array_filter(array_merge($ac_amounts, $non_ac_amounts));
        if (in_array($property->propertyType->slug, ['hostel-pg', 'guest-hotel', 'hostel-pg-one-day'])) {
            $starting_amount = min($amounts);
            $property->update(['starting_amount' => $starting_amount]);
        } else {
            $property->update(['starting_amount' => $property->amount]);
        }

        $property->propertyRooms()->insert($insert);
    }

    public function updatePropertyProcessSteps($request, $id)
    {
        $_CURRENT_STEP = (int) $request->get('step');
        session()->put(['current_step' => $_CURRENT_STEP]);
        $message = 'Property details updated.';
        $_NEXT_STEP = $_CURRENT_STEP + 1;
        $stepData['step_' . $_CURRENT_STEP] = $request->all();
        if ($_CURRENT_STEP < 4) {
            $insert['current_step'] = $_CURRENT_STEP + 1;
        }
        if (auth()->user()->hasRole('admin')) {
            $property = $this->Property->where('id', $id)->first();
        } else {
            $property = $this->Property->where('user_id', auth()->user()->id)->where('id', $id)->first();
        }
        if (!$property) {
            $data = [
                'message' => 'Sorry!!, This record is not available',
                'status_code' => 205,
                'type' => 'error',
                'current_step' => 'step_' . $_CURRENT_STEP,
                'next_step' => 'step_' . $_NEXT_STEP,
                'step' => $_NEXT_STEP,
            ];
        }
        $stepAlls = $request->all();
        if (auth()->user()->hasRole('admin')) {
            unset($stepAlls['user_id']);
        }

        // pr($stepAlls);
        if ($_CURRENT_STEP == 1) {
            $stepAlls['area_id'] = $stepAlls['area'];
            $stepAlls['city_id'] = $stepAlls['city'];
            $update = $property->update($stepAlls);
            if($update){
                $updateAddress = $this->Property->with(['area','city','state'])->find($property->id);
                if($updateAddress){
                    $areaName = ($updateAddress->area) ? $updateAddress->area->name : '';
                    $cityName = ($updateAddress->city) ? $updateAddress->city->name : '';
                    $stateName = ($updateAddress->state) ? $updateAddress->state->name : '';
                    $search_address = $areaName.', '.$cityName.', '.$stateName;
                    $updateAddress->search_address = $search_address;
                    $updateAddress->save();
                }
            }
            $data = [
                'message' => $message,
                'status_code' => 205,
                'type' => 'success',
                'current_step' => 'step_' . $_CURRENT_STEP,
                'next_step' => 'step_' . $_NEXT_STEP,
                'step' => $_NEXT_STEP,
                'scroll' => '150',
            ];
            return $data;
        }

        if ($_CURRENT_STEP == 2) {
            $stepAlls['available_for_names'] = json_encode(@$stepAlls['available_fors']);
            if ($property->update($stepAlls)) {
                $this->savePropertyAvailableFor($property);
                $this->savePropertyRooms($property, $stepAlls, true);
                $data = [
                    'image_panel' => true,
                    'active_rooms' => json_encode($stepAlls['room_type'], JSON_FORCE_OBJECT),
                    'message' => $message,
                    'status_code' => 205,
                    'type' => 'success',
                    'current_step' => 'step_' . $_CURRENT_STEP,
                    'next_step' => 'step_' . $_NEXT_STEP,
                    'step' => $_NEXT_STEP,
                    'scroll' => '150',
                ];
            } else {
                $data = [
                    'message' => trans('flash.error.oops_something_went_wrong_updating_record'),
                    'status_code' => 400,
                    'type' => 'error'
                ];
            }
            return $data;
        }
        if ($_CURRENT_STEP == 3) {
            $stepAlls['amenities_ids'] = json_encode(@$stepAlls['amenities_id']);
            if ($property->update($stepAlls)) {
                $this->savePropertyAmenities($property);
                $this->savePropertyRoomImages($property, $stepAlls);
                $data = [
                    'message' => $message,
                    'status_code' => 205,
                    'type' => 'success',
                    'current_step' => 'step_' . $_CURRENT_STEP,
                    'next_step' => 'step_' . $_NEXT_STEP,
                    'step' => $_NEXT_STEP,
                    'scroll' => '150',
                ];
                return $data;
            }
            $data = [
                'message' => trans('flash.error.oops_something_went_wrong_updating_record'),
                'status_code' => 400,
                'type' => 'error'
            ];
            return $data;
        }
        if ($_NEXT_STEP == 5) {
            if ($property->update($stepAlls)) {
                unset($stepAlls['_method']);
                unset($stepAlls['_token']);
                unset($stepAlls['user_id']);
                unset($stepAlls['step']);
                if ($property->propertyPaymentInfo) {
                    $property->propertyPaymentInfo()->update($stepAlls);
                } else {
                    $property->propertyPaymentInfo()->create($stepAlls);
                }
                $message = 'All property details updated successfully.';
                $data = [
                    'message' => $message,
                    'status_code' => 205,
                    'type' => 'success',
                    'url' => route('vendor.myproperty'),
                ];
                if (auth()->user()->hasRole('admin')) {
                    $data['url'] = route('property.index');
                }
                session()->forget('current_step');
                return $data;
            }
            $data = [
                'message' => trans('flash.error.oops_something_went_wrong_updating_record'),
                'status_code' => 400,
                'type' => 'error'
            ];
            return $data;
        }
        $data = [
            'message' => trans('flash.error.oops_something_went_wrong_updating_record'),
            'status_code' => 400,
            'type' => 'error'
        ];
        return $data;
    }

    public function savePropertyRoomImages($property, $stepAlls)
    {
        $property->propertyRoomImages()->delete();
        $x = 0;
        // pr($stepAlls);
        foreach ($property->propertyRooms as $k => $room) {
            foreach ($stepAlls[$room->room_type . '_room_images'] as $k2 => $roomImage) {
                $insert[$x]['property_id'] = $property->id;
                $insert[$x]['property_room_id'] = $room->id;
                $insert[$x]['room_image'] = $roomImage;
                $insert[$x]['room_type'] = $room->room_type;
                $insert[$x]['created_at'] = now();
                $insert[$x]['updated_at'] = now();
                $x++;
            }
        }
        // pr($insert);
        $property->propertyRoomImages()->insert($insert);
    }

    public function deleteSessionEntryDataBySlug($slug)
    {
        $data = $this->PropertySessionEntry->where('slug', $slug)->first();
        if ($data) {
            return $data->delete();
        }
        return false;
    }

    public function savePropertyAmenities($property)
    {
        $ids_Arr = json_decode($property->amenities_ids);
        $property->propertyAmenities()->delete();
        if ($ids_Arr) {
            if (count($ids_Arr) > 0) {
                foreach ($ids_Arr as $k => $id) {
                    $insert[$k]['property_id'] = $property->id;
                    $insert[$k]['amenitiy_id'] = $id;
                    $insert[$k]['created_at'] = now();
                    $insert[$k]['updated_at'] = now();
                }
                $property->propertyAmenities()->insert($insert);
            }
        }
    }

    public function savePropertyAvailableFor($property)
    {
        $names_Arr = json_decode($property->available_for_names);
        $property->propertyAvailableFor()->delete();
        if ($names_Arr) {
            if (count($names_Arr) > 0) {
                foreach ($names_Arr as $k => $available_for) {
                    $insert[$k]['property_id'] = $property->id;
                    $insert[$k]['available_for'] = $available_for;
                    $insert[$k]['created_at'] = now();
                    $insert[$k]['updated_at'] = now();
                }
                $property->propertyAvailableFor()->insert($insert);
            }
        }
    }

    public function getMyWishlist($request)
    {
        $wishlist = $this->userWishListClass->whereHas('property', function ($q) {
        })->with(['property'])
            ->where(['object_model' => 'property', 'user_id' => auth()->user()->id])
            ->orderBy('id', 'desc');
        return $wishlist->paginate(8);
    }

    public function uploadRoomImages($request, $user_id)
    {
        $height = 350;
        $width = 380;
        $filename = uploadWithResize($request->file('files'), '/property/' . $user_id . '/rooms/', $height, $width);
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['full_path'] = \Storage::disk('s3')->url('property/' . $user_id . '/rooms/thumbnail/' . $filename);
        return $response;
    }

    public function uploadRoomVideo($request, $user_id )
    {
        $filename = uploadOnS3Bucket($request->file('files'), '/property/' . $user_id . '/rooms/');
        $response['type'] = 'success';
        $response['status_code'] = 200;
        $response['filename'] = $filename;
        $response['full_path'] = \Storage::disk('s3')->url('property/' . $user_id . '/rooms/' . $filename);
        $response['message'] = 'Video uploaded successfully';
        return $response;
    }

    public function propertyUploadAgreementMedia($request, $user_id)
    {

        $filename = uploadOnS3Bucket($request->file('files'), '/property/' . $user_id . '/');
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['status_code'] = 250;
        return $response;
    }
}
