<?php

namespace Modules\Payment\Http\Controllers;

use View,Session,Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Repositories\PaymentRepositoryInterface as PaymentRepo;

class PaymentController extends Controller
{
    public function __construct(PaymentRepo $PaymentsRepo)
    {
        $this->middleware(['ability','auth','prevent-back-history']);
        $this->PaymentsRepo = $PaymentsRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $records = $this->PaymentsRepo->getAllRecordsWithFilter($request);
        
        if ($request->ajax()) {
            return Response::json(array('body' =>json_encode(View::make('payment::ajax_payment_list',compact('records'))->withModel('payment')->render())));
        }
        return view('payment::index',compact('records'))->withModel('payment')->with([
            'statictsData' => $this->PaymentsRepo->getPaymentStatisticBlockData($request),
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug, Request $request) {
        $payment =  $this->PaymentsRepo->getRecordBySlug($slug);
        if($payment ){
          return view('payment::show',compact('payment'))->withModel('payment');  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('payments.index');
    }
}
