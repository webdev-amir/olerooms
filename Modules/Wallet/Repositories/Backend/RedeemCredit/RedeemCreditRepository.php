<?php

namespace Modules\Wallet\Repositories\Backend\RedeemCredit;

use Modules\Wallet\Entities\RedeemCreditRequest;
use Modules\Wallet\Entities\Wallet;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepo;

class RedeemCreditRepository implements RedeemCreditRepositoryInterface
{

    public $RedeemCreditRequest;
    protected $model = 'RedeemCreditRequest';
    public $EmailNotificationsRepo;
    public $Wallet;

    function __construct(
        Wallet $Wallet,
        RedeemCreditRequest $RedeemCreditRequest,
        EmailNotificationsRepo $EmailNotificationsRepo
    ) {
        $this->Wallet = $Wallet;
        $this->RedeemCreditRequest = $RedeemCreditRequest;
        $this->EmailNotificationsRepo = $EmailNotificationsRepo;
    }

    public function getRecord($id)
    {
        return $this->RedeemCreditRequest->find($id);
    }

    public function getRecordBySlug($slug)
    {
        return $this->RedeemCreditRequest->findBySlug($slug);
    }

    public function getAllRecords($request)
    {
        $redeem = $this->RedeemCreditRequest;
        $status = NULL;
        if ($request->get('status')) {
            $status = $request->get('status');
            $redeem = $redeem->where('redeem_credit_request.status', '=', $status);
        }
        $to  = $request->get('to');
        $from  = $request->get('from');
        if (!empty($from)) {
            $from  = date("Y-m-d", strtotime($from));
            $redeem = $redeem->where('created_at', '>=', $from);
        }
        if (!empty($to)) {
            $to  = date("Y-m-d", strtotime($to . "+1 day"));
            $redeem = $redeem->where('created_at', '<=', $to);
        }
        return $redeem->latest()->paginate(config('custom.default_pagination'));
    }

    public function updateRedeemStatus($request)
    {
        $redeem = $this->getRecord($request->get('id'));
        if ($redeem) {
            $redeem->status = $request->get('status');
            $redeem->comments = $request->get('comments');
            $status_html = $completedDate = $rejectedDate = '';
            if ($request->get('status') == 'completed') {
                $redeem->transactionid = $request->get('transactionid');
                $redeem->completed_date = now();
                $status_html = '<span class="label btext-completed">COMPLETED</span>';
                $status_text = 'Completed';
                $message = 'Redeem request accepted successfully.';
                $completedDate = $redeem->completed_date->format(config('custom.default_date_formate'));
            }
            if ($request->get('status') == 'rejected') {
                $redeem->rejected_date = now();
                $status_html = '<span class="label btext-rejected">REJECTED</span>';
                $status_text = 'Rejected';
                $message = 'Redeem request rejected successfully.';
                $rejectedDate = $redeem->rejected_date->format(config('custom.default_date_formate'));
            }
            if ($redeem->save()) {
                $this->EmailNotificationsRepo->sendCreditRedeemStatusEmail($redeem);
                if ($redeem->status == 'rejected') {
                    $this->walletAMountReverseOnDeclinedWathdrawRequestByAdmin($redeem);
                }
                $response['message'] = $message;
                $response['type'] = 'success';
                $response['status_code'] = 200;
                $response['id'] = $redeem->id;
                $response['statusHtml'] = $status_html;
                $response['statusText'] = $status_text;
                $response['rejectedDate'] = $rejectedDate;
                $response['completedDate'] = $completedDate;
                $response['transactionid'] = $redeem->transactionid;
                $response['comments'] = $redeem->comments;
                $response['htmlId'] = 'chngStatus' . $redeem->id;
                $response['htmlTextId'] = 'chngTextStatus' . $redeem->id;
            } else {
                $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
                $response['type'] = 'error';
                $response['status_code'] = 400;
            }
            return $response;
        }
    }

    public function walletAMountReverseOnDeclinedWathdrawRequestByAdmin($redeem)
    {
        $this->Wallet->create([
            'user_id' => $redeem->user_id,
            'type' => 'credit',
            'status' => 'rejected',
            'amount' => $redeem->amount,
            'description' => 'Redeem Credit amount reversed to wallet on withdraw request rejected',
        ]);
    }
}
