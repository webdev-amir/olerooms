<?php

namespace Modules\Booking\Repositories\Backend;

use config, DB, Session;
use DataTables;
use Carbon\Carbon;
use Modules\Booking\Entities\Booking;
use Modules\Property\Entities\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepositoryInterface as EmailNotificationsRepository;

class CancelScheduleVisitRepository implements CancelScheduleVisitRepositoryInterface
{

    public $ScheduleVisit;
    protected $model = 'Booking';

    function __construct(ScheduleVisit $ScheduleVisit, User $User, Property $Property, EmailNotificationsRepository $EmailNotificationsRepository)
    {
        $this->ScheduleVisit = $ScheduleVisit;
        $this->Users = $User;
        $this->Property = $Property;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
    }

    public function getRecord($id)
    {
        return $this->ScheduleVisit->find($id);
    }

    public function getRecordBySlug($slug)
    {
        $record = $this->ScheduleVisit->findBySlug($slug);

        return $record;
    }

    public function getAllRecords($request)
    {
        $bookings = $this->Booking->whereHas('author', function (Builder $q) use ($request) {
            if ($request->get('username')) {
                $q->where('username', 'like', '%' . $request->get('username') . '%');
            }
        })->whereIn('status', AcceptedCancelStatus)->whereNotIn('status', ['completed', 'rejected']);
        if ($request->get('search')) {
            $bookings = $bookings->whereHas('space', function (Builder $qa) use ($request) {
                $qa->where('title', 'like', '%' . $request->get('search') . '%');
            });
        }

        $status = NULL;
        if ($request->get('status')) {
            $bookings = $bookings->where('bookings.status', '=', $request->get('status'));
        }

        $to  = $request->get('to');
        $from  = $request->get('from');

        if ($request->get('from') && $request->get('to')) {
            $start_date = date('Y-m-d', strtotime($request->get('from')));
            $end_date = date('Y-m-d', strtotime($request->get('to')));

            $bookings =  $bookings->whereBetween('start_date', [$start_date, $end_date]);
            $bookings =  $bookings->whereBetween('end_date', [$start_date, $end_date]);
        }

        $book = $bookings->orderBy('id', 'desc')->paginate(config::get('custom.default_pagination'));
        return $book;
    }

    /**
     * search by keywork using like
     * @return search result
     */
    public function searchLike($q, $query)
    {
        return $q->where('bookings.title', 'like', "%{$query}%");
    }

    public function destroy($id)
    {
        if ($this->Booking->whereIn('status', ['request', 'unpaid'])->where('id', $id)->exists()) {
            return $this->Booking->destroy($id);
        } else {
            return response()->json(['status_code' => 200, 'type' => 'error', 'message' => trans('flash.error.oops_reocrd_not_available')]);
        }
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->ScheduleVisit->where('cancel_request_date', '!=', NULL)->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with(['customer', 'scheduleVisitProperty.property']);
            if ($request->has('username')) {
                $username = $request->username;
                $lists->whereHas('customer', function ($query) use ($username) {
                    $query->where([['users.name', 'like', '%' . $username . '%']]);
                });
            }
            if ($request->has('emailId')) {
                $lists->where('email', 'LIKE', '%' . $request->emailId . '%');
            }
            if ($request->has('bookingId')) {
                $lists->where('schedule_code', $request->bookingId);
            }
            if ($request->has('vendor')) {
                $lists->where('vendor_id', $request->vendor);
            }
            if ($request->has('transactionId')) {
                $lists->whereHas('payment', function ($query) use ($request) {
                    $query->where("payments.transaction_id", $request->transactionId);
                });
                //$lists->where('payment_id',$request->transactionId);
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
                $lists->whereBetween('cancel_request_date', [date('Y-m-d', strtotime($request->srart_date)), date('Y-m-d', strtotime($request->end_date))]);
            }
            $lists = $lists->latest()->get();
            return DataTables::of($lists)
                ->addColumn('action', function ($list) use ($model) {
                    $dispalyButton = displayButton(['view' => [strtolower($model) . '.cancelschedulevisit.show', [$list->slug]], 'bookingCancelled' => [strtolower($model) . '.cancelschedulevisit.status', [$list->slug]], 'bookingRejected' => [strtolower($model) . '.cancelschedulevisit.status', [$list->slug]]]);
                    $statusCancelled = $statusRejected = $edit = $delete = '';
                    // if ($list->status !== 'cancelled') {
                    if ($list->CancellationStatusDate == 'N/A') {

                        $statusCancelled = keyExist($dispalyButton, 'bookingCancelled');
                        $statusRejected = keyExist($dispalyButton, 'bookingRejected');
                    }
                    $edit = keyExist($dispalyButton, 'view');
                    return $statusCancelled . $statusRejected . $edit;
                })
                ->editColumn('id', function ($list) {
                    return $list->schedule_code;
                })
                ->editColumn('user_name', function ($list) {
                    return $list->customer->name . '<br>' . $list->customer->email . '<br>' . $list->customer->phone;
                })
                ->editColumn('request_date', function ($list) {
                    return ($list->cancel_request_date ? date('d M Y, g:i a', strtotime($list->cancel_request_date)) : "N/A");
                })
                ->editColumn('amount', function ($list) {
                    return ($list->total ? numberformatWithCurrency($list->total) : 'N/A');
                })
                ->editColumn('transaction_id', function ($list) {
                    return ($list->payment ? $list->payment->transaction_id : "N/A");
                })
                ->editColumn('cancellation_reason', function ($list) {
                    return ($list->cancellation_reason ? substr($list->cancellation_reason, 0, 20) : "");
                })
                ->editColumn('visit_status', function ($list) {
                    if ($list->status == 'cancelled') {
                        $class = 'cancelled';
                        $label = 'Cancelled';
                    } else {
                        $class = 'pending';
                        $label = 'Pending';
                    }
                    return "<span class='label btext-" . $class . "'>" . ucfirst($label) . "</span>";
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

                ->rawColumns(['visit_status', 'action', 'cancellation_status','user_name'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function changeStatus($request, $slug)
    {
        $response = [];
        try {
            $booking = $this->getRecordBySlug($slug);
            if ($booking) {
                $filleable['title'] = 'Scheduled visit cancelled';
                $filleable['body'] = 'Scheduled visit has been cancelled';
                $filleable['type'] = ' schedule';
                $filleable['id'] = ($booking) ? $booking->id : "";
                if ($request->get('title') == 'Accept' && $booking->status == "cancelled") {
                    $message =  "Already cancelled!";
                    $type = 'warning';
                } else {
                    if ($request->get('title') == 'Accept') {
                        $user =  $booking->customer;
                        if ($user) {
                            $tokenss =  $user;
                        }
                        $status = 'accepted';
                        $booking->status = "cancelled";
                        $booking->schedule_visit_cancelled_date = now();
                        $booking->save();
                        $message = "Schedule Visit cancel request accepted successfully!";
                        $type = 'success';
                        sendPushNotificationForScheduling($filleable, $tokenss);
                    } else {
                        $status = 'declined';
                        $booking->schedule_visit_cancelled_reject_date = now();
                        $booking->cancel_request_date = NULL;
                        $booking->cancellation_reason = '';
                        $booking->save();
                        $message = "Schedule Visit cancel request declined successfully!";
                        $type = 'success';
                    }

                    $this->EmailNotificationsRepository->sendVisitCancellationRequestEmailUser($booking, $status);
                    foreach ($booking->scheduleVisitProperty as $visitProperty) {
                        $this->EmailNotificationsRepository->sendVisitCancellationRequestEmailVendor($booking, $status, $visitProperty);
                    }
                }
            } else {
                $message =  "Schedule Visit not found!";
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

    public function getVendors()
    {
        return $this->Users->where('role_id', 3)->orderBy('name')->pluck('name', 'id');
    }
}
