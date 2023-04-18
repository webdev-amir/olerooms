<?php

namespace Modules\Cron\Repositories;

use Modules\Booking\Entities\Booking;
use Modules\Property\Entities\Property;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepository;
use Modules\Notifications\Repositories\NotificationRepository as NotificationRepository;
use Modules\Booking\Repositories\Frontend\BookingRepository as BookingRepository;

class CronRepository implements CronRepository
{
    public $Property;
    public $Booking;
    public $EmailNotificationsRepository;
    public $NotificationsRepository;
    public $BookingRepository;
    

    function __construct(
        Property $Property,
        Booking $Booking,
        EmailNotificationsRepository $EmailNotificationsRepository,
        NotificationRepository $NotificationRepository,
        BookingRepository $BookingRepository
    ) {
        $this->Property = $Property;
        $this->Booking = $Booking;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->NotificationsRepository = $NotificationRepository;
        $this->BookingRepository = $BookingRepository;
    }


    public function sendTestMailandNotificationCron()
    {
        $response = $this->EmailNotificationsRepository->sendTestMailandNotificationCronEmail();
        return $response;
    }

    public function makePendingToAutoConfirmedAndRejectedIfBookingIsNotConfirmedByVendor()
    {
        $autoConfirmedBufferMinutes = setting_item('auto-confirmation');
        $afterBufferTime = now()->subMinutes($autoConfirmedBufferMinutes);
        $bookings = $this->Booking->where('status', 'pending')->where('created_at', '<=', $afterBufferTime)->get();
        if (count($bookings) > 0) {
            foreach ($bookings as  $booking) {
                $room_occupancy_type = isset(json_decode($booking->property_booking_data)->room_occupancy_type) ? json_decode($booking->property_booking_data)->room_occupancy_type : '';
                $property_room_type = isset(json_decode($booking->property_booking_data)->property_room_type) ? json_decode($booking->property_booking_data)->property_room_type : '';

                $property = $this->Property->where('id', $booking->property_id)->with(['propertyType'])->withTrashed()->first();

                if ($booking->booked_by == 'company') {
                    $request =   json_decode($booking->property_booking_data, true);
                    $currentBookingGuests = 0;
                } else {
                    $currentBookingGuests = isset(json_decode($booking->property_booking_data)->guests) ? json_decode($booking->property_booking_data)->guests : 1;
                    $request = [];
                }

                $error = $this->BookingRepository->getAvailableSeatsbyPropertyID($booking->property_id, $booking->check_in_date, $booking->check_out_date ?? '', $room_occupancy_type, $property_room_type, '', $property, $request, $booking->booked_by, $booking->id, $currentBookingGuests);

                if ($error) {
                    $this->rejectBookingRequest($booking);
                } else {
                    $this->acceptBookingRequest($booking);
                }
            }
            $response['message'] = 'Booking marked to auto confirmed successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        } else {
            $response['message'] = 'No Booking available to mark pending to auto confirmed';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        }
    }

    public function acceptBookingRequest($booking)
    {
        if ($booking->status == $booking::PENDING) {
            $booking->status = $booking::CONFIRMED;
            $booking->booking_reject_confirm_type = $booking::AUTOCONFIRMED;
            $booking->booking_confirmed_date = now();
            if ($booking->save()) {
                $this->EmailNotificationsRepository->sendBookingConfirmedEmailForUser($booking);

                $notificationData = [
                    'user_id' => $booking->user_id,
                    'data' => json_encode([
                        'notification' => [
                            'link' => route('customer.dashboard.mybooking'),
                            'message' => 'Your booking has auto confirmed.',
                            'name' => $booking->vendor->FullName
                        ]
                    ])
                ];
                $this->NotificationsRepository->addNotification($notificationData);
                $response['message'] = 'Booking confirmed Successfully';
                $response['type'] = 'success';
                $response['status_code'] = 200;
                $response['refresh'] = 'true';
                $response['show_msg'] = 'true';
            } else {
                $response['message'] = 'Booking not confirmed please try again later';
                $response['type'] = 'success';
                $response['status_code'] = 400;
                $response['show_msg'] = 'true';
            }
        } else {
            $response['message'] = 'Booking not confirmed please try again later';
            $response['type'] = 'success';
            $response['status_code'] = 400;
            $response['show_msg'] = 'true';
        }
    }

    public function rejectBookingRequest($booking)
    {
        if ($booking->status == $booking::PENDING) {
            $booking->status = $booking::REJECTED;
            $booking->booking_reject_confirm_type = $booking::AUTOREJECTED;
            $booking->booking_reject_date = now();
            if ($booking->save()) {

                $this->EmailNotificationsRepository->sendBookingRejectedEmailForUser($booking);
                $notificationData = [
                    'user_id' => $booking->user_id,
                    'data' => json_encode([
                        'notification' => [
                            'link' => route('customer.dashboard.mybooking'),
                            'message' => 'Your Booking has auto rejected',
                            'name' => $booking->vendor->FullName
                        ]
                    ])
                ];
                $this->NotificationsRepository->addNotification($notificationData);
                $response['message'] = 'Booking rejected Successfully';
                $response['type'] = 'success';
                $response['status_code'] = 200;
                $response['refresh'] = 'true';
                $response['show_msg'] = 'true';
            } else {
                $response['message'] = 'Booking not rejected please try again later';
                $response['type'] = 'success';
                $response['status_code'] = 400;
                $response['show_msg'] = 'true';
            }
            return $response;
        }
    }

    public function makeConfirmedToCompletedIfBookingCheckOutDateIsPassed()
    {
        $bookings = $this->Booking->where('status', $this->Booking::CONFIRMED)->where('custom_chekout_date', '<', now())->whereHas('property.propertyType', function ($query) {
        })->get();
        if (count($bookings) > 0) {
            foreach ($bookings as $key => $booking) {
                $booking->status = $this->Booking::COMPLETED;
                $booking->booking_completed_date = now();
                $booking->save();
            }
            $response['message'] = 'Booking marked to auto completed successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        } else {
            $response['message'] = 'No Booking available to mark confirmed to auto completed';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        }
    }

    public function makeSearchAddressForSearch()
    {
        $property = $this->Property->with(['area', 'city', 'state'])->get();
        if (count($property) > 0) {
            foreach ($property as  $list) {
                $areaName = ($list->area) ? $list->area->name : '';
                $cityName = ($list->city) ? $list->city->name : '';
                $stateName = ($list->state) ? $list->state->name : '';
                $search_address = $areaName . ', ' . $cityName . ', ' . $stateName;
                $list->search_address = $search_address;
                $list->save();
            }
            $response['message'] = 'Search Address Updated completed successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        } else {
            $response['message'] = 'Search Address not Updated';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            return $response;
        }
    }
}
