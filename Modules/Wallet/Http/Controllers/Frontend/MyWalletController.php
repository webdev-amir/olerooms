<?php

namespace Modules\Wallet\Http\Controllers\Frontend;

use Session,View,Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Http\Requests\ProceedToWalletPaymentRequest;
use Modules\Wallet\Repositories\Frontend\FrontendWalletRepositoryInterface as FrontendWalletRepo;

class MyWalletController extends Controller
{
    public function __construct(FrontendWalletRepo $FrontendWalletRepo)
    {
        $this->middleware(['auth','prevent-back-history']);
        $this->FrontendWalletRepo = $FrontendWalletRepo;
    }
    /**
     * get the Wallet page.
     * @return Response
     */
    public function myWallet(Request $request)
    {
         return view('wallet::dashboard.my_wallet');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function proceedToAddWalletMoneyPayForm($slug)
    {
        $record = $this->FrontendWalletRepo->getWalletTempRecordForPayment($slug);
        return view('wallet::dashboard.proceed_to_wallet_payment_form',compact('record'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function makeWalletPayment(ProceedToWalletPaymentRequest $request)
    {
        if($this->FrontendWalletRepo->getWalletTempRecordById($request->get('id'))){
            $response = $this->FrontendWalletRepo->paystackChargePaymentAndAddMoneyInWallet($request);
            if ($request->ajax()) {
                return response()->json($response);
            }
            if($response['status_code'] == 205){
                return redirect($response['url'])->with('success', $response['message']);
            }
            return redirect()->back()->with('error', $response['message']);
        }
        Session::flash('error', trans('flash.error.oops_something_went_wrong_invalid_access')); 
        return redirect()->back();
    }

    /**
     * get the Reedom Credit Page.
     * @return Response
     */
    public function redeemCredit(Request $request)
    {
         return view('wallet::dashboard.redeem_credit');
    }

    /**
     * get the Reedom Credit Page.
     * @return Response
     */
    public function walletPaymentSummary(Request $request)
    {
        $records = $this->FrontendWalletRepo->getMywalletPaymentSummaryList($request);
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('wallet::dashboard.ajax_wallet_payment_summarry_list',compact('records'))->withModel('wallet')->render())));
        }
        return view('wallet::dashboard.wallet_payment_summary',compact('records'));
    }
}