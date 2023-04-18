<?php

namespace Modules\Wallet\Repositories\Frontend;

use DB,Mail,Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletVerification;
use Modules\Wallet\Entities\TempProceedToAddMoneyWallet;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\Payments\Entities\Payments;

class FrontendWalletRepository implements FrontendWalletRepositoryInterface {


    function __construct(Wallet $Wallet, WalletVerification $WalletVerification,TempProceedToAddMoneyWallet $TempProceedToAddMoneyWallet,Payments $Payments) {
        $this->Wallet = $Wallet;
        $this->Payments = $Payments;
        $this->WalletVerification = $WalletVerification;
        $this->TempProceedToAddMoneyWallet = $TempProceedToAddMoneyWallet;
    }

    public function getTempWalletRecordBySlug($slug)
    {
        $check = $this->TempProceedToAddMoneyWallet->findBySlug($slug);
        return ($check) ? $check->id : NULL;
    }

    public function getWalletTempRecordForPayment($slug)
    {
        return $this->TempProceedToAddMoneyWallet->findOrFail($this->getTempWalletRecordBySlug($slug)); 
    }

    public function getWalletTempRecordById($id)
    {
        return $this->TempProceedToAddMoneyWallet->find($id); 
    }  

    public function paystackChargePaymentAndAddMoneyInWallet($request)
    {
        $payment = $this->payStackCharge($request);
        return $payment;
    }

    public function payStackCharge($request)
    {
        $tempData = $this->getWalletTempRecordById($request->get('id'));
        if($tempData){
            $tempsessionid=$tempData->id;
            $amount = $tempData->amount;  //the amount in kobo. This value is actually NGN 300
            if( $request->get('status')=='charge' ){
                $cardnumber = preg_replace('/(?<=\d)\s+(?=\d)/', '', $request->get('number'));
                $expiryArr = explode('/',preg_replace('/(?<=\d)\s+(?=\d)/', '', $request->get('expiry')));
                $curl = curl_init();
                $email = env('MERCHANT_EMAIL');
                $card['cvv'] = $request->get('cvc');
                $card['number'] = $cardnumber;
                $card['expiry_month'] = $expiryArr[0];
                $card['expiry_year'] = $expiryArr[1];
                  //url to go to after payment
                  curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.paystack.co/charge",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode([
                    'amount'=> $amount * 100,
                    'email'=> $email,
                    'card' => $card,
                   // 'pin'  => "1234",
                  ]),
                  CURLOPT_HTTPHEADER => [
                    "authorization: Bearer ".env('PAYSTACK_SECRET_KEY')."", //replace this with your own test key
                    "content-type: application/json",
                    "cache-control: no-cache"
                  ],
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                /*if($err){
                  // there was an error contacting the Paystack API
                  die('Curl returned error: ' . $err);
                }*/
                $tranx = json_decode($response, true);
                if(!$tranx['status']){
                    $message = $tranx['message'];
                   // there was an error from the API
                    if(isset($tranx['data']) && $tranx['data']['message']){
                         $message = $tranx['data']['message'];
                    }
                    $data = [
                        'message' => $message,
                        'status_code' => 500,
                        'type'=> 'error',
                    ];
                    return $data;
                }elseif ($tranx['data']['status'] == 'send_pin') {
                    $data = $tranx['data'];
                    return array('status_code'=>207,'html' =>json_encode(\View::make('wallet::dashboard.submit_pin_model',compact('data','tempsessionid'))->render()));
                }elseif ($tranx['data']['status'] == 'send_otp') {
                    $data = $tranx['data'];
                    return array('status_code'=>207,'html' =>json_encode(\View::make('wallet::dashboard.submit_otp_model',compact('data','tempsessionid'))->render()));
                } 
            }else if( $request->get('status')=='send_pin' ){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.paystack.co/charge/submit_pin",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode([
                    'reference'=> $request->get('reference'),
                    'pin'  => $request->get('pin'),
                  ]),
                  CURLOPT_HTTPHEADER => [
                    "authorization: Bearer ".env('PAYSTACK_SECRET_KEY')."",
                    "content-type: application/json",
                    "cache-control: no-cache"
                  ],
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                $tranx = json_decode($response, true);

                if(!$tranx['status']){
                    $message = $tranx['message'];
                   // there was an error from the API
                    if(isset($tranx['data']) && $tranx['data']['message']){
                         $message = $tranx['data']['message'];
                    }
                    $data = [
                        'message' => $message,
                        'status_code' => 500,
                        'type'=> 'error',
                    ];
                    return $data;
                }elseif ($tranx['data']['status'] == 'send_otp') {
                    $data = $tranx['data'];
                    return array('status_code'=>207,'html' =>json_encode(\View::make('wallet::dashboard.submit_otp_model',compact('data','tempsessionid'))->render()));
                }elseif ($tranx['data']['status'] == 'send_phone') {
                    $data = $tranx['data'];
                    $data = [
                        'message' => 'Sorry!!,Phone verification not allowed',
                        'status_code' => 500,
                        'type'=> 'error',
                    ];
                     return $data;
                } 
            }else if( $request->get('status')=='send_otp' ){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.paystack.co/charge/submit_otp",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => json_encode([
                    'reference'=> $request->get('reference'),
                    'otp'  => $request->get('otp'),
                  ]),
                  CURLOPT_HTTPHEADER => [
                    "authorization: Bearer ".env('PAYSTACK_SECRET_KEY')."",
                    "content-type: application/json",
                    "cache-control: no-cache"
                  ],
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                $tranx = json_decode($response, true);
                if(!$tranx['status']){
                    $message = $tranx['message'];
                   // there was an error from the API
                    if(isset($tranx['data']) && $tranx['data']['message']){
                         $message = $tranx['data']['message'];
                    }
                    $data = [
                        'message' => $message,
                        'status_code' => 500,
                        'type'=> 'error',
                    ];
                    return $data;
                }
            }
            if(isset($tranx)){
                //comment out this line if you want to redirect the user to the payment page
                $payData = $this->savePaymentDetailsAfterPayment($tranx);
                $walletData = $this->addCreditAMountIntoWallet($amount);
                if(auth()->user()->hasRole('investors')){
                  $url = route('investor.myWallet');
                }else{
                  $url = route('borrower.myWallet');
                }
                $data = [
                    'message' => trans('flash.success.your_money_added_successfully_into_wallet'),
                    'status_code' => 200,
                    'type'=> 'success',
                    'url'=> $url,
                    'reset'=> 'true',
                ];
                $tempData->delete();
                return $data;
            } 
        }else{
            if(auth()->user()->hasRole('investors')){
              $url = route('investor.myWallet');
            }else{
              $url = route('borrower.myWallet');
            }
            $data = [
                'message' => trans('flash.error.session_has_been_expired'),
                'status_code' => 500,
                'type'=> 'error',
                'url'=> $url,
            ];
            return $data; 
        }
    }

    public function addCreditAMountIntoWallet($amount)
    {
        return $this->Wallet->create([
            'user_id' => auth()->user()->id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => trans('menu.added_to_wallet'),
        ]);
    } 

    public function savePaymentDetailsAfterPayment($res)
    {
        return $this->Payments->create([
            'user_id'   => auth()->user()->id,
            'payment_type'   => 'wallet',
            'amount'    => $res['data']['amount']/100,
            'total'     => $res['data']['amount']/100,
            'reference' => $res['data']['reference'],
            'TranID'    => $res['data']['id'],
            'TranDate'  => $res['data']['paid_at'],
            'ip_address'=> $res['data']['ip_address'],
            'channel'   => $res['data']['channel'],
            'status'    => $res['data']['status'],
            'currency'  => $res['data']['currency'],
            'domain'    => $res['data']['domain'],
        ]);
    }

    public function getMywalletPaymentSummaryList($request)
    {
        $summary = $this->Wallet->where('user_id',auth()->user()->id);
        $to  = date("Y-m-d",strtotime($request->get('to')."+1 day"));
        $from  = date("Y-m-d",strtotime($request->get('from')));
        $sortbyParam  = $request->get('sortby');
        if(!empty($sortbyParam)){
            $sortArray = explode('|',$sortbyParam);
            if(count($sortArray)==2){
                $sortField= explode('|', $sortbyParam)[0];
                $sortByValue = explode('|', $sortbyParam)[1];
                $summary  = $summary->orderBy($sortField,$sortByValue);
            }
        }
        if(!empty($from)){
            $summary = $summary->where('created_at', '>=', $from);
        }
        if(!empty($to)){
            $summary = $summary->where('created_at', '<=', $to);
        }
        return $summary->latest()->paginate(10);
    }

    //Not use yet
    public function verifyPayment($refrence)
    {
        $curl = curl_init();
        $reference = 634172216;
        if(!$reference){
          die('No reference supplied');
        }
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer sk_test_47924835a73df000a5d62861bb91c73861bdfbdf",
            "cache-control: no-cache"
          ],
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if($err){
            // there was an error contacting the Paystack API
          die('Curl returned error: ' . $err);
        }
        $tranx = json_decode($response);
        if(!$tranx->status){
          // there was an error from the API
          die('API returned error: ' . $tranx->message);
        }
        if('success' == $tranx->data->status){
            pr($tranx);
          // transaction was successful...
          // please check other things like whether you already gave value for this ref
          // if the email matches the customer who owns the product etc
          // Give value
          echo "<h2>Thank you for making a purchase. Your file has bee sent your email.</h2>"; die;
        }
    }  
}
