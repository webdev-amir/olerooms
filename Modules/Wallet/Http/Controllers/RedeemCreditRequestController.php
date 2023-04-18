<?php

namespace Modules\Wallet\Http\Controllers;

use View,Session,Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Wallet\Repositories\Backend\RedeemCredit\RedeemCreditRepositoryInterface as RedeemCreditRepo;

class RedeemCreditRequestController extends Controller
{
    public function __construct(RedeemCreditRepo $RedeemCreditRepo)
    {
        $this->middleware(['ability','auth','prevent-back-history']);
        $this->RedeemCreditRepo = $RedeemCreditRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    { 
        $records = $this->RedeemCreditRepo->getAllRecords($request);
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('wallet::redeemcredit.ajax_redeemcredit_list',compact('records'))->withModel('wallet')->render())));
        }
        return view('wallet::redeemcredit.index',compact('records'))->withModel('wallet');
    }    

    public function getConfirmBox(Request $request)
    { 
        $data = $request->all();
        return view('wallet::redeemcredit.redeem_credit_confirm_model',compact('data'))->withModel('wallet');
    } 

    public function changeRedeemStatus(Request $request)
    { 
        $response = $this->RedeemCreditRepo->updateRedeemStatus($request);
        if ($request->ajax()) {
            return Response::json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return back();
    }
}
