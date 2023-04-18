<?php

namespace Modules\Booking\Repositories\Backend;

use config,
    DB,
    Session;
use DataTables;
use Carbon\Carbon;
use Modules\Booking\Entities\Booking; 
use Modules\Property\Entities\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;

class CancelBookingRepository implements CancelBookingRepositoryInterface {

    public $Booking;
    protected $model = 'Booking';

    function __construct(
            Booking $Booking,
            User $User,
            Property $Property,
            EmailNotificationsRepository $EmailNotificationsRepository
    ) {
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
        $this->Booking = $Booking;
        $this->Users = $User;
        $this->Property = $Property;
    }

    public function getRecord($id) {
        return $this->Booking->find($id);
    }

    public function getRecordBySlug($slug) {
        $record = $this->Booking->findBySlug($slug);

        return $record;
    }

    /**
     * search by keywork using like
     * @return search result
     */
    public function searchLike($q, $query) {
        return $q->where('bookings.title', 'like', "%{$query}%");
    }

    public function destroy($id) {
        if ($this->Booking->whereIn('status', ['request', 'unpaid'])->where('id', $id)->exists()) {
            return $this->Booking->destroy($id);
        } else {
            return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
        }
    }

    public function getAjaxData($request) {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->where('booked_by', 'customer')->where('cancel_request_date', '!=', NULL)->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with(['user', 'vendor', 'property', 'property.propertyType'])->whereNotIn('status',['completed','rejected']);
            if ($request->has('username')) {
                $username = $request->username;
                $lists->whereHas('user', function ($query) use ($username) {
                    $query->where([['users.username', 'like', '%' . $username . '%']]);
                });
            }

            if ($request->has('emailId')) {
                $lists->where('email', 'LIKE', '%' . $request->emailId . '%');
            }
            if ($request->has('bookingId')) {
                $lists->where('code', $request->bookingId);
            }
            if ($request->has('vendor')) {
                $lists->where('vendor_id', $request->vendor);
            }
            if ($request->has('transactionId')) {
                $lists->where('payment_id', $request->transactionId);
            }
            if ($request->has('statusBooking')) {
                $lists->where('status', $request->statusBooking);
            }
            if ($request->has('proType')) {
                $property_type = $request->proType;
                $lists->whereHas('property.propertyType', function ($query) use ($property_type) {
                    $query->where("property_types.id", $property_type);
                });
            }
            if ($request->has('srart_date') && $request->has('end_date')) {
                $lists->where(function ($query) use ($request) {
                    $query->where('check_in_date', '>=', date('Y-m-d', strtotime($request->srart_date)))->where('check_in_date', '<=', date('Y-m-d', strtotime($request->end_date)))
                            ->orWhere('check_out_date', '>=', date('Y-m-d', strtotime($request->srart_date)))->where('check_out_date', '<=', date('Y-m-d', strtotime($request->end_date)));
                });
            }
            $lists = $lists->latest()->get();
            return DataTables::of($lists)
                            ->addColumn('action', function ($list) use ($model) {
                                $dispalyButton = displayButton(['view' => [strtolower($model) . '.cancelbooking.show', [$list->slug]], 'bookingCancelled' => [strtolower($model) . '.cancelbooking.status', [$list->slug]], 'bookingRejected' => [strtolower($model) . '.cancelbooking.status', [$list->slug]]]);
                                $statusCancelled = $statusRejected = $edit = $delete = '';
                                if ($list->CancellationStatusDate == 'N/A') {
                                    $statusCancelled = keyExist($dispalyButton, 'bookingCancelled');
                                    $statusRejected = keyExist($dispalyButton, 'bookingRejected');
                                }
                                $edit = keyExist($dispalyButton, 'view');
                                return $statusCancelled . $statusRejected . $edit;
                            })
                            ->editColumn('id', function ($list) {
                                return ($list->code ? $list->code : 'N/A');
                            })
                            ->editColumn('user_name', function ($list) {
                                return $list->customer->name . '<br>' . $list->customer->email . '<br>' . $list->customer->phone;
                            })
                            ->editColumn('pro_owner', function ($list) {
                                return ($list->vendor ? $list->vendor->name : "N/A");
                            })
                            ->editColumn('property_cat', function ($list) {
                                return ($list->property ? ($list->property->propertyType ? $list->property->propertyType->name : "") : "");
                            })
                            ->editColumn('booking_date', function ($list) {
                                return (date('d M Y, g:i a', strtotime($list->created_at)));
                            })
                            ->editColumn('checkIn_date', function ($list) {
                                return ($list->check_in_date ? date('d M Y', strtotime($list->check_in_date)) : "N/A");
                            })
                            ->editColumn('checkOut_date', function ($list) {
                                return ($list->check_out_date ? date('d M Y', strtotime($list->check_out_date)) : "N/A");
                            })
                            ->editColumn('location', function ($list) {
                                return \Illuminate\Support\Str::limit($list->property ? $list->property->map_location : "N/A", 100, '');
                            })
                            ->editColumn('amount', function ($list) {
                                return numberformatWithCurrency($list->total);
                            })
                            ->editColumn('transaction_id', function ($list) {
                                return ($list->payment ? $list->payment->transaction_id : "N/A");
                            })
                            ->editColumn('booking_status', function ($list) {
                                return "<span class='label btext-" . $list->status . "'>" . ucfirst($list->status) . "</span>";
                            })
                            ->editColumn('cancellation_status', function ($list) {
                                if ($list->CancellationStatus == 'accepted') {
                                    $class = 'confirmed';
                                } else if ($list->CancellationStatus == 'rejected') {
                                    $class = 'cancelled';
                                } else {
                                    $class = 'pending';
                                }
                                return "<span class='label btext-" . $class . "'>" . ucfirst($list->CancellationStatus) . "</span>";
                            })
                            ->editColumn('cancellation_status_date', function ($list) {
                                return $list->CancellationStatusDate;
                            })
                            ->rawColumns(['status', 'action', 'booking_status', 'cancellation_status','user_name'])
                            ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function changeStatus($request, $slug) {
        $response = [];
        try {
            $booking = $this->getRecordBySlug($slug);
            if ($booking) {
                $filleable['title'] = 'Booking cancelled';
                $filleable['body'] = 'Booking has been cancelled';
                $filleable['type'] = ' booking';
                $filleable['slug'] = ($booking) ? $booking->slug : "";
                if ($request->get('title') == 'Accept' && $booking->status == "cancelled") {
                    $message = "Already cancelled!";
                    $type = 'warning';
                } else {
                    if ($request->get('title') == 'Accept') {
                        $user =  $booking->customer;
                        if($user){
                            $tokenss =  $user;
                        }
                        $status = 'accepted';
                        $booking->status = "cancelled";
                        $booking->booking_cancelled_date = now();
                        $booking->save();
                        $message = "Booking cancel request accepted successfully!";
                        $type = 'success';
                        sendPushNotificationForBooking($filleable,$tokenss);
                    } else {
                        $status = 'declined';
                        $booking->booking_cancelled_reject_date = now();
                        $booking->cancel_request_date = NULL;
                        $booking->cancellation_reason = '';
                        $booking->save();
                        $message = "Booking cancel request declined successfully!";
                        $type = 'success';
                    }
                    $this->EmailNotificationsRepository->sendBookingCancellationRequestEmailToUserByAdmin($booking, $status);
                    $this->EmailNotificationsRepository->sendBookingCancellationRequestEmailToVendorByAdmin($booking, $status);
                }
            } else {
                $message = "Booking not found!";
                $type = 'warning';
            }
            $response['status_code'] = 200;
            $response['message'] = $message;
            $response['type'] = $type;
            return $response;
        } catch (\Exception $e) {
            $response['status_code'] = 400;
            $response['message'] = $e->getMessage();
            $response['type'] = 'error';
            Session::flash($response['type'], $response['message']);
            return $response;
        }
    }

    public function getVendors() {
        return $this->Users->where('role_id', 3)->orderBy('name')->pluck('name', 'id');
    }

}
