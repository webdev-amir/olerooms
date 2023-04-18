<?php

namespace Modules\Api\Repositories\Customer\Property;

use Validator;
use Razorpay\Api\Api;
use App\Models\User;
use Modules\Payment\Entities\Payment;
use Modules\Property\Entities\Property;
use Modules\PropertyType\Entities\PropertyType;
use Modules\Users\Entities\UserWishlist;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;
use Modules\Booking\Entities\Booking;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;

class PropertyRepository implements PropertyRepositoryInterface
{

    function __construct(User $User, Property $Property, PropertyType $PropertyType, UserWishlist $Userwishlist, ScheduleVisit $ScheduleVisit, ScheduleVisitProperty $ScheduleVisitProperty, EmailNotificationsRepository $EmailNotificationsRepository, Booking $Booking)
    {
        $this->User = $User;
        $this->Property = $Property;
        $this->PropertyType = $PropertyType;
        $this->Userwishlist = $Userwishlist;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->ScheduleVisitProperty = $ScheduleVisitProperty;
        $this->Booking = $Booking;
        $this->paymentClass = Payment::class;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
    }
    public function addFavorite($request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'is_favorite' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $property = $this->Property->where('id', $request->property_id)->first();
            if ($property) {
                $favoriteStatus = $request->is_favorite;
                $wishListQuery = $this->Userwishlist->where('object_id', $request->property_id)
                    ->where('object_model', 'property')->where('user_id', auth()
                        ->user()->id);
                if ($favoriteStatus == 1) {
                    $favoriteExist = $wishListQuery->first();
                    if ($favoriteExist) {
                        $response['status_code'] = 200;
                        $response['message'] = 'property already in favorite list';
                    } else {
                        $this->Userwishlist->create([
                            'object_id' => $request->property_id,
                            'object_model' => 'property',
                            'user_id' => auth()->user()->id
                        ]);
                        $response['status_code'] = 200;
                        $response['message'] = 'property favorite sucessfully';
                        $response['data'] = $property;
                        $response['data']['is_favorite'] = 1;
                        return response()->json($response, $response['status_code'])
                            ->withHeaders(checkVersionStatus(
                                $request->headers->get('Platform'),
                                $request->headers->get('Version')
                            ))->setStatusCode($response['status_code']);
                    }
                } else if ($favoriteStatus == 0) {
                    $favoriteExist = $wishListQuery->first();
                    if (!$favoriteExist) {
                        $response['status_code'] = 200;
                        $response['message'] = 'property already in Unfavorite list';
                    } else {
                        $wishListQuery->delete();
                        $response['status_code'] = 200;
                        $response['message'] = 'property Unfavorite sucessfully';
                        $response['data'] = $property;
                        $response['data']['is_favorite'] = 0;
                    }
                } else {
                    $response['status_code'] = 200;
                    $response['message'] = 'please enter a valid favorite status 0 or 1';
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'property id does not match to our records';
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

    public function myWishlist($request)
    {
        $wishlist = $this->Userwishlist->where('user_id', auth()->user()->id)->with('property')->latest()->paginate(10);
        $response = paginationFormat($wishlist);
        $response['status_code'] = 200;
        $response['message'] = 'your wishlist';

        if (count($wishlist) > 0) {
            foreach ($wishlist as $key => $wishlistProperty) {
                $response['data']['property'][$key]['id'] = $wishlistProperty->property->id;
                $response['data']['property'][$key]['slug'] = $wishlistProperty->property->slug;
                $response['data']['property'][$key]['property_type'] = $wishlistProperty->property->propertyType->name;
                $response['data']['property'][$key]['iswishlist'] = $wishlistProperty->property->hasWishList != null ? 1 : 0;
                $response['data']['property'][$key]['image'] = $wishlistProperty->property->CoverImg;
                $response['data']['property'][$key]['property_code'] = $wishlistProperty->property->property_code;
                $response['data']['property'][$key]['city'] = $wishlistProperty->property->city->name;
                $response['data']['property'][$key]['price'] = numberformatWithCurrency($wishlistProperty->property->starting_amount);
                $response['data']['property'][$key]['rating'] = $wishlistProperty->property->RatingAverage;
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'No property found';
        }

        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function propertyDetail($request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        try {
            $response['status_code'] = 200;
            $response['message'] = 'property detail';
            $propertyinfo = $this->Property->where('id', $request->property_id)->first();

            if ($propertyinfo) {
                $slug = $propertyinfo->slug;
                if ($slug) {
                    $property = $this->getRecordBySlug($slug);
                    if ($property) {
                        $similarProperty = $this->getSimilarProperty($property);
                        $response['data']['property']['type']  = $property->PropertyType->name;
                        if (
                            $property->author->userCompleteProfileVerifired && $property->author->ComponyLogo !=
                            '' && config('custom.is_company_logo_show')
                        ) {
                            $response['data']['property']['company_logo'] = $property->author->ComponyLogo;
                        } else {
                            $response['data']['property']['company_logo'] = '';
                        }

                        $response['data']['property']['iswishlist'] = $property->hasWishList != null ? 1 : 0;
                        $response['data']['property']['property_code'] = $property->property_code;
                        $response['data']['property']['reting_average'] = $property->RatingAverage;
                        $response['data']['property']['overview'] = $property->property_description;
                        if (!empty($property->propertyAvailableFor)) {
                            $availableForFilter = config('custom.property_available_for');
                            foreach ($property->propertyAvailableFor as $key => $propertyAvailableFor) {
                                $response['data']['property']['information']['avaialable_for'][$key]['value'] = $availableForFilter[$propertyAvailableFor->available_for];
                            }
                        }
                        if (!empty($property->furnished_type)) {

                            $response['data']['property']['information']['furnished_type'][0]['value'] = $property->FurnishedTypeValue;
                        } else {
                            $response['data']['property']['information']['furnished_type'][0]["value"] = 'not furnished';
                        }
                        if (!empty($property->total_seats)) {
                            $response['data']['property']['information']['total_seats'] = $property->total_seats;
                        }

                        $response['data']['property']['room_start_at'] = numberformatWithCurrency($property->PropertStartingAmount);
                        $response['data']['property']['city'] = $property->city->name;
                        $response['data']['property']['schedule_visit_amount'] = numberformatWithCurrency(setting_item('schedule_visit_amount'));
                        if ($property->propertyAmenities) {
                            foreach ($property->propertyAmenities as $key => $amenity) {
                                $response['data']['property']['amenity'][$key]['images'] = $amenity->amenities->PicturePath;
                                $response['data']['property']['amenity'][$key]['name'] = $amenity->amenities->name;
                            }
                        }
                        if (isset($property->carpet_area)) {
                            $response['data']['property']['carpet_area'] = $property->CarpetAreaInSq;
                        }
                        if (isset($property->kitchen_modular)) {
                            $response['data']['property']['kitchen_modular'] = ucfirst($property->kitchen_modular);
                        }
                        if (isset($property->parking_space_avail)) {
                            $response['data']['property']['parking_space_avail'] = ucfirst($property->parking_space_avail);
                        }

                        if ($property->room_type) {
                            foreach ($property->room_type as $key => $occupancy) {

                                $response['data']['property']['information']['occupancy'][$key]['value'] = ucfirst($occupancy);
                                $response['data']['property']['information']['occupancy'][$key]['food_availability'] = $property->ac_is_food_included == 1 ? 'Yes' : 'No';
                                // $response['data']['property']['information']['food_availability'][$key]['value'] = $property->ac_is_food_included == 1 ? 'Yes' : 'No';
                            }
                        }
                        if (count($property->propertyTotalRoomImages) > 0) {
                            $x = 1;
                            $response['data']['property']['images'][0] = $property->CoverImg;
                            foreach ($property->propertyTotalRoomImages as $key => $list) {
                                $response['data']['property']['images'][$x] = $list->RoomImageThunbnail;
                                $x++;
                            }
                        } else {
                            $response['data']['property']['images'][0] = $property->CoverImg;
                        }
                        $response['data']['property']['review']['total_reviews'] = count($property->propertyReviews);
                        if (count($property->propertyReviews) > 0) {
                            foreach ($property->propertyReviews as $key => $review) {
                                $response['data']['property']['review']['username'] = $review->user->name;
                                $response['data']['property']['review']['userimage'] = $review->user->PicturePath;
                                $response['data']['property']['review']['publish_date'] =
                                    get_date_month_name($review->publish_date);
                                $response['data']['property']['review']['rating'] = $review->rate_number;
                                $response['data']['property']['review']['content'] = $review->content;
                                if ($review->reply_content) {
                                    $response['data']['property']['review']['ownerimage'] = $review->vendor->PicturePath;
                                    $response['data']['property']['review']['owner_reply'] = $review->reply_content;
                                } else {
                                    $response['data']['property']['review']['ownerimage'] = '';
                                    $response['data']['property']['review']['owner_reply'] = '';
                                }
                            }
                        } else {
                            $response['data']['property']['review'] = null;
                        }
                        $response['data']['property']['url'] = route('manageProperty.show', [$property->slug]);

                        if (isset($property->map_location)) {
                            $response['data']['property']['map']['lat'] = $property->DummyLat;
                            $response['data']['property']['map']['long'] = $property->DummyLong;
                            $response['data']['property']['map']['location'] = $property->search_address;
                        } else {
                            $response['data']['property']['map'] = array();
                        }
                        if ($property->YoutubeEmbededUrl) {
                            $response['data']['property']['youtube_url'] = $property->YoutubeEmbededUrl;
                        }
                    }
                    if ($similarProperty) {
                        foreach ($similarProperty as $key => $item) {
                            $response['data']['similar_property'][$key]['type'] = $item->PropertyType->name;
                            $response['data']['similar_property'][$key]['slug'] = $item->slug;
                            $response['data']['similar_property'][$key]['id'] = $item->id;
                            $response['data']['similar_property'][$key]['cover_img'] = $item->CoverImg;
                            $response['data']['similar_property'][$key]['iswishlist'] = $item->hasWishList != null ? 1 : 0;
                            $response['data']['similar_property'][$key]['property_code'] = $item->property_code;
                            $response['data']['similar_property'][$key]['city'] = $item->city->name;
                            $response['data']['similar_property'][$key]['price'] =
                                numberformatWithCurrency($item->starting_amount);
                            $response['data']['similar_property'][$key]['rating'] = $item->RatingAverage;
                            $response['data']['similar_property'][$key]['schedule_visit_amount'] = numberformatWithCurrency(setting_item('schedule_visit_amount'));
                        }
                    }
                }
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'no property found';
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

    public function getRecordBySlug($slug)
    {
        $record =  $this->Property->with(['propertyAmenities', 'propertyRooms', 'propertyPaymentInfo'])
            ->where('slug', $slug)->first();
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
                            'non_ac_is_food_included' => $propRoom->non_ac_is_food_included
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
    public function getSimilarProperty($property)
    {
        $record =  $record =  $this->Property->where([['id', '!=', $property->id], ['property_type_id', $property->property_type_id], ['is_publish', true,], ['status', 'publish'], ['amount', '!=', 0]])
            // ->where('map_location', 'LIKE', '%' . $property->map_location . '%')
            ->whereBetween('lat', [$property->lat, $property->long])
            ->whereBetween('long', [$property->lat, $property->long])
            ->whereHas('author', function ($query) {
                $query->where('users.status', 1);
            })->whereHas('author.userCompleteProfileVerifiredIfApproved')->orderBy('status', 'asc')->take(8)->get();
        return $record;
    }
    public function propertyReviewListing($request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $property = $this->Property->where('id', $request->property_id)->first();
        if ($property) {
            $response['status_code'] = 200;
            $response['message'] = 'property reviews list';
            if (count($property->propertyReviews) > 0) {
                foreach ($property->propertyReviews as $key => $review) {

                    $response['property']['review'][$key]['userimage'] = $review->user->PicturePath;
                    $response['property']['review'][$key]['username'] = $review->user->name;
                    $response['property']['review'][$key]['publish_date'] = get_date_month_name($review->publish_date);
                    $response['property']['review'][$key]['rating'] = $review->rate_number;
                    $response['property']['review'][$key]['content'] = $review->content;
                    if ($review->reply_content) {
                        $response['property']['review'][$key]['ownerimage'] = $review->vendor->PicturePath;
                        $response['property']['review'][$key]['owner_reply'] = $review->reply_content;
                        $response['property']['review'][$key]['owner_reply_date'] = get_date_month_name($review->reply_date);
                    } else {
                        $response['property']['review'][$key]['ownerimage'] = '';
                        $response['property']['review'][$key]['owner_reply'] = '';
                        $response['property']['review'][$key]['owner_reply_date'] = '';
                    }
                }
                return response()->json($response, $response['status_code'])
                    ->withHeaders(checkVersionStatus(
                        $request->headers->get('Platform'),
                        $request->headers->get('Version')
                    ))->setStatusCode($response['status_code']);
            } else {
                return response()->json(['status_code' => 200, 'message' => 'no review found'], 200);
            }
        } else {
            return response()->json(['status_code' => 200, 'message' => 'no property found'], 200);
        }
    }

    public function scheduleGetOrderId($request)
    {
        $validator = Validator::make($request->all(), [
            'amount'      => 'required',
            'request_id'  => 'required',
            'bookingtype' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
        $bookingtype = ($request->get('bookingtype')) ? $request->get('bookingtype') : '';
        if ($bookingtype == 'Booking' || $bookingtype == 'ScheduleVisit') {
            $booking  = $this->$bookingtype->select('id', 'total')->where('id', $request->get('request_id'))->first();
            if ($booking) {
                if ($booking->total < 1 && $bookingtype == 'ScheduleVisit') {
                    $response['status_code'] = 200;
                    $response['message'] = 'First Please submit property details properly, The amount must be atleast INR 1.00';
                }
                if ($booking->total < 1) {
                    $response['status_code'] = 200;
                    $response['message'] = 'The order amount must be atleast INR 1.00';
                }
                $order = $api->order->create(array('receipt' => $booking->id, 'amount' => $booking->total * 100, 'currency' => config('paymentsetting.currency')));
                $response['status_code'] = 200;
                $response['message'] = 'order data';
                $response['data']['order_id'] = $order['id'];
                $response['data']['pay_amount'] = $booking->total;
                $response['data']['bookingtype'] = $bookingtype;
                $response['data']['type_id'] = $booking->id;
            } else {
                $response['status_code'] = 200;
                $response['message'] = 'Something went wrong with this booking';
            }
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'Something went wrong with this booking';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }

    public function makePayment($request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id'      => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
        $payment = $api->payment->fetch($request->input('razorpay_payment_id'));
        $saved = false;
        if (!empty($payment) && $payment['status'] == 'captured') {
            $ordertype = isset($payment['notes']['type']) ? $payment['notes']['type'] : '';
            $order_id = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
            $order = $this->$ordertype::where('id', $order_id)->first();
            $saved = $this->savePaymentDetailsForAfterPayment($payment, $order, $ordertype);
        } else {
            return redirect()->back()->with('error', 'Something went wrong, Please try again later!');
        }
        if ($saved) {
            $filleable['title'] = 'Scheduled visit confirm';
            $filleable['body'] = 'Scheduled visit has been confirmed';
            $filleable['type'] = ' scheduling';
            $tokenss = auth()->user()->whereNotNull('device_token')->first('device_token', 'id');
            $filleable['id'] = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
            sendPushNotificationForScheduling($filleable, $tokenss);
            $response['status_code'] = 200;
            $response['message'] = 'booking sucessfully';
        } else {
            $response['status_code'] = 200;
            $response['message'] = 'not found';
        }
        return response()->json($response, $response['status_code'])
            ->withHeaders(checkVersionStatus(
                $request->headers->get('Platform'),
                $request->headers->get('Version')
            ))->setStatusCode($response['status_code']);
    }
    public function savePaymentDetailsForAfterPayment($payment, $order, $ordertype)
    {
        $pay['type'] = $ordertype;
        $pay['type_id'] = $order->id;
        $pay['payment_gateway'] = defaultPaymentGateway();
        $pay['transaction_id'] = $payment->id;
        $pay['amount'] = $payment->amount / 100;
        $pay['currency'] = $payment->currency;
        $pay['entity'] = $payment->entity;
        $pay['order_id'] = $payment->order_id;
        $pay['status'] = $payment->status;
        $pay['method'] = $payment->method;
        $pay['bank'] = $payment->bank;
        $pay['wallet'] = $payment->wallet;
        $pay['email'] = $payment->email;
        $pay['contact'] = $payment->contact;
        $pay['logs'] = json_encode($payment);
        $pay['create_user'] = $order->user_id;
        $pay['ip_address'] = request()->ip();
        $bankTranstionId = isset($payment['acquirer_data']['bank_transaction_id']) ? $payment['acquirer_data']['bank_transaction_id'] : '';
        $pay['bank_transaction_id'] = $bankTranstionId;

        if ($orderPayment = $this->paymentClass::create($pay)) {
            $saved = true;
            if (isset($order->schedule_billing_data)) {
                $order->schedule_billing_data = json_encode($orderPayment);
            }
            if ($ordertype == 'ScheduleVisit') {
                $order->status = 'confirmed';
                $order->payment_id = $orderPayment->id;
                $order->save();
                $visits = $this->ScheduleVisitProperty->where('schedule_visits_id', $order->id)
                    ->with(['property'])->get();
                foreach ($visits as $visit) {
                    $vendor = $this->User->where('id', $visit->property->user_id)->first();
                    $booking = array(
                        'customer_id' => $order->customer->id,
                        'customer_name' => auth()->user()->name,
                        'property_name' => $visit->property->property_name,
                        'slug' => $visit->slug,
                        'schedule_code' => $order->schedule_code,
                        'customer_image_path' => $order->customer->PicturePath,
                    );
                    $this->EmailNotificationsRepository->sendSchduleVisitBookingEmail($vendor, $booking);
                    $this->EmailNotificationsRepository->sendSchduleVisitBookingEmailUser($order, $booking);
                }
            } else {
                $order->property_billing_data = json_encode($orderPayment);
                $order->status = Booking::PENDING;
                $order->payment_id = $orderPayment->id;
                $order->save();
                $this->EmailNotificationsRepository->sendPropertyBookingEmailVendor($order);
                //$this->EmailNotificationsRepository->sendPropertyBookingEmailUser($order);
            }
        }
        return $saved;
    }
}
