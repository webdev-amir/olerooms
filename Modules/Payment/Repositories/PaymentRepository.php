<?php

namespace Modules\Payment\Repositories;

use DB, Mail, Session, DataTables;
use Illuminate\Support\Facades\Input;
use Modules\Payment\Entities\Payment;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notifications\Repositories\NotificationRepositoryInterface as NotificationRepositoryInterface;

class PaymentRepository implements PaymentRepositoryInterface
{

    public $Payments;
    protected $model = 'Payment';

    function __construct(Payment $Payments, NotificationRepositoryInterface $NotificationRepositoryInterface)
    {
        $this->Payments = $Payments;
        $this->NotificationsRepository = $NotificationRepositoryInterface;
    }

    public function getRecord($id)
    {
        return $this->Payments->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->Payments->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
                ->addColumn('action', function ($list) {
                    $dispalyButton = displayButton(['view' => ['payments.show', [$list->slug]]]);
                    $view = keyExist($dispalyButton, 'view');
                    return $view;
                })
                ->editColumn('username', function ($list) {
                    return $list->user->FullName;
                })
                ->editColumn('created_at', function ($list) {
                    return $list->FullTranDate;
                })
                ->editColumn('amount', function ($list) {
                    return numberformatWithCurrency($list->amount, 2);
                })
                ->editColumn('status', function ($list) {
                    $status = $list->status == 'captured' ? 'success' : 'danger';
                    return "<span class='label btext-" . $list->status . "'>" . ucfirst($list->status) . "</span>";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getAllRecordsWithFilter($request)
    {
        $payment = $this->Payments;
        $status = NULL;
        if ($request->get('status')) {
            $status = $request->get('status');
            $filterStatus = ($status == 'active') ? $filterStatus = 1 : $filterStatus = 0;
            $payment = $payment->whereHas('user', function (Builder $q) use ($filterStatus) {
                $q->where('status', $filterStatus);
            });
        }
        if ($request->get('search')) {
            $searchKey = $request->get('search');
            $payment = $payment->whereHas('user', function (Builder $q) use ($searchKey) {
                $q->where('name', 'like', '%' . $searchKey . '%');
            });
        }
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $from  = date("Y-m-d", strtotime($from));
            $payment = $payment->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $to  = date("Y-m-d", strtotime($to . "+1 day"));
            $payment = $payment->where('created_at', '<=', $to);
        }
        return $payment->latest()->paginate(\config::get('custom.default_pagination'));
    }

    public function getPaymentStatisticBlockData($request)
    {
        $response['totalAmount'] = numberformatWithCurrency($this->Payments->sum('amount'));
        return $response;
    }

    public function createOrderWithoutPaymentProcessByAdmin($planid, $user_id)
    {
        $plan = $this->Plans->findOrFail($planid);
        if ($plan) {
            $order = [
                'paymob_order_id'   =>  null,
                'paymob_amount'     =>  0,
                'currency'          =>  null,
                'user_id'           =>  $user_id,
                'payment_type'      =>  'Plan',
                'payment_type_id'   =>  $planid,
                'is_paid'   =>  true
            ];
            $orderdata = Order::create($order);
            $payment = $this->savePaymentEntry($orderdata);
            $this->activatePlanToOrderPaymentUser($payment, $orderdata);
            $notificationData = [
                'user_id' => $user_id,
                'data' => [
                    'notification' => [
                        'message' =>  'Your plan has been updated by admin. Enjoy Bytewrite.',
                        'name' => $orderdata->user->name
                    ]
                ]
            ];
            $this->NotificationsRepository->addNotification($notificationData);
            $response['message'] = 'Plan successfully subscribed';
            $response['type'] = 'success';
            $response['action'] = 'modal';
        } else {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        }
        return $response;
    }

    public function savePaymentEntry($orderdata)
    {
        $insert['user_id'] = $orderdata->user_id;
        $insert['paid_by'] = auth()->user()->id;
        $insert['payment_type'] = $orderdata->payment_type;
        $insert['payment_type_id'] = $orderdata->id;
        $insert['amount'] = 0;
        $insert['total'] = 0;
        $insert['ip_address'] = request()->ip();
        $insert['status'] = $orderdata->is_paid ? 'success' : 'failed';
        $insert['TranID'] = rand();
        $insert['TranDate'] = now();
        $insert['logdata'] = null;
        return Payments::create($insert);
    }


    public function activatePlanToOrderPaymentUser($payment, $orderdata)
    {
        $plan = $orderdata->plan;
        $insert['plan_id'] = $plan->id;
        $insert['user_id'] = $payment->user_id;
        $insert['payment_id'] = $payment->id;
        $insert['no_of_tasks'] = $plan->no_of_tasks;
        $insert['no_of_review'] = $plan->no_of_review;
        $insert['plan_type'] = $plan->plan_type;
        if ($plan->plan_type == 'monthly') {
            $insert['expired_at'] = now()->addMonth();
        }
        if ($plan->plan_type == 'yearly') {
            $insert['expired_at'] = now()->addYear();
        }
        $insert['amount'] = $plan->amount;
        $insert['status'] = 'Subscribed';
        $insert['status_date'] = now();
        $changeStatusOldOfPlan = UserPlan::where('user_id', $payment->user_id)->orderBy('id', 'desc')->first();
        if ($changeStatusOldOfPlan) {
            if ($changeStatusOldOfPlan->plan->trial == 0) {
                $remainingHours = now()->diffInHours($changeStatusOldOfPlan->expired_at);
                if ($plan->plan_type == 'monthly') {
                    $insert['expired_at'] = now()->addMonth()->addHours($remainingHours);
                }
                if ($plan->plan_type == 'yearly') {
                    $insert['expired_at'] = now()->addYear()->addHours($remainingHours);
                }
            }
            $expiry_date = $changeStatusOldOfPlan->expired_at;
            $current_date = now();
            $changeStatusOldOfPlan->status = 'Upgraded';
            if ($current_date > $expiry_date) {
                $changeStatusOldOfPlan->status = 'Expired';
            }
            $changeStatusOldOfPlan->status_date = now();
            $changeStatusOldOfPlan->save();
        }
        return UserPlan::create($insert);
    }
}
