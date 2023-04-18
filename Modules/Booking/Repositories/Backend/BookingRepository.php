<?php

namespace Modules\Booking\Repositories\Backend;

use config, DB, Session;
use DataTables;
use Carbon\Carbon;
use Modules\Booking\Entities\Booking;
use Modules\Property\Entities\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BookingRepository implements BookingRepositoryInterface
{

    public $Booking;
    protected $model = 'Booking';

    function __construct(Booking $Booking, User $User, Property $Property)
    {
        $this->Booking = $Booking;
        $this->Users = $User;
        $this->Property = $Property;
    }

    public function getRecord($id)
    {
        return $this->Booking->find($id);
    }

    public function getRecordBySlug($slug)
    {
        $record = $this->Booking->findBySlug($slug);

        return $record;
    }



    public function getAllRecords($request)
    {
        $bookings = $this->Booking->whereHas('author', function (Builder $q) use ($request) {
            if ($request->get('username')) {
                $q->where('username', 'like', '%' . $request->get('username') . '%');
            }
        });
        if ($request->get('search')) {
            $bookings = $bookings->whereHas('space', function (Builder $qa) use ($request) {
                $qa->where('title', 'like', '%' . $request->get('search') . '%');
            });
        }

        $status = NULL;
        if ($request->get('status')) {
            $bookings = $bookings->where('bookings.status', '=', $request->get('status'));
        }
        if ($request->get('strid')) {
            //$bookings = $bookings->where('bookings.storage_id', '=', $request->get('strid'));
        }

        $to  = $request->get('to');
        $from  = $request->get('from');

        if ($request->get('from') && $request->get('to')) {
            $start_date = date('Y-m-d', strtotime($request->get('from')));
            $end_date = date('Y-m-d', strtotime($request->get('to')));

            $bookings =  $bookings->whereBetween('start_date', [$start_date, $end_date]);
            $bookings =  $bookings->whereBetween('end_date', [$start_date, $end_date]);
        }

        $book = $bookings->where('booked_by', 'customer')->orderBy('id', 'desc')->paginate(config::get('custom.default_pagination'));
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

    public function getHostCommisionHtml($slug)
    {
        $booking = $this->Booking->where('slug', $slug)->first();
        $res['body'] = json_encode(\View::make('booking::includes.ajax_set_host_commission_html', compact('booking'))->render());
        return $res;
    }

    public function settHostCommision($slug, $request)
    {
        $booking = $this->Booking->where('slug', $slug)->first();
        if ($booking) {
            $booking->update($request->all());
            $response['message'] = 'Host commission updated successfully';
            $response['type'] = 'success';
            $response['status_code'] = 200;
            $response['modelClose'] = 'modelContentLarge';
        } else {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->where('booked_by', 'customer')->whereNotIn('status', Booking::notAcceptedStatus)->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with(['user', 'vendor', 'property', 'property.propertyType']);
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
                $lists->where('id', $request->bookingId);
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
                    $dispalyButton = displayButton([
                        'view' => [
                            strtolower($model) . '.show',
                            [$list->slug]
                        ],
                        'Cancelled' => [strtolower($model) . '.status', [$list->slug]]
                    ]);
                    $status = $edit = $delete = '';
                    if ($list->status != 'cancelled') {
                        // $status = keyExist($dispalyButton, 'Cancelled');
                    }
                    $edit = keyExist($dispalyButton, 'view');
                    return $status . $edit;
                })
                ->editColumn('id', function ($list) {
                    return $list->id;
                })
                ->editColumn('code', function ($list) {
                    $booking_date = date('d M Y, g:i a', strtotime($list->created_at));
                    return $list->code . '<br>' . $booking_date;
                })
                ->editColumn('property_name', function ($list) {
                    $property_name = ($list->property ? ($list->property->property_name ? $list->property->property_name : "") : "");
                    $property_type = ($list->property ? ($list->property->propertyType ? $list->property->propertyType->name : "") : "");
                    return $property_name . '<br>' . $property_type;
                })
                ->editColumn('user_name', function ($list) {
                    return $list->customer->name . '<br>' . $list->customer->email . '<br>' . $list->customer->phone;
                })
                ->editColumn('pro_owner', function ($list) {
                    return ($list->vendor ? $list->vendor->name : "N/A");
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
                ->editColumn('status', function ($list) {
                    if ($list->status == 'pending') {
                        return "<span class='label btext-" . $list->status . "'>" . ucfirst($list->status) . "</span>";
                    } else {
                        return "<span class='label btext-" . $list->status . "'>" . ucfirst($list->status) . "</span>";
                    }
                })
                ->rawColumns(['status', 'action', 'code', 'property_name','user_name'])
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
                if ($booking->status != 'cancelled') {
                    $booking->status = "cancelled";
                    $booking->save();
                    $message = "Status updated successfully!";
                    $type = 'success';
                } else {
                    $message =  "Already cancelled!";
                    $type = 'warning';
                }
            } else {
                $message =  "Booking not found!";
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
