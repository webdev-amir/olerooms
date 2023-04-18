<?php

namespace Modules\Payment\Repositories\Frontend\RozarPay;

use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Modules\Booking\Entities\Booking;
use Modules\ScheduleVisit\Entities\ScheduleVisit;
use Modules\ScheduleVisit\Entities\ScheduleVisitProperty;
use Modules\Payment\Entities\Payment;
use Modules\EmailNotifications\Repositories\EmailNotificationsRepository as EmailNotificationsRepository;
use App\Models\User;
use Modules\Wallet\Entities\Wallet;

class RozarPayPaymentRepository implements RozarPayPaymentRepositoryInterface
{

    protected $Booking;
    protected $ScheduleVisit;
    protected $ScheduleVisitProperty;
    protected $paymentClass;
    protected $EmailNotificationsRepository;
    protected $User;

    function __construct(Booking $Booking, ScheduleVisit $ScheduleVisit, ScheduleVisitProperty $ScheduleVisitProperty, EmailNotificationsRepository $EmailNotificationsRepository, User $User)
    {
        $this->Booking = $Booking;
        $this->User = $User;
        $this->ScheduleVisit = $ScheduleVisit;
        $this->ScheduleVisitProperty = $ScheduleVisitProperty;
        $this->paymentClass = Payment::class;
        $this->EmailNotificationsRepository = $EmailNotificationsRepository;
    }

    public function makeBookingPaymentWithRozarPayResponse($request)
    {
        $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
        $payment = $api->payment->fetch($request->input('razorpay_payment_id'));
        $saved = false;
        if (!empty($payment) && $payment['status'] == 'captured') {
            $ordertype = isset($payment['notes']['type']) ? $payment['notes']['type'] : '';
            $order_id = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
            $order = $this->$ordertype::where('id', $order_id)->first();
            $saved = true;
            //IF webhook on working then custom payment entry make for booking
            if (!$order->payment) {
                $saved = $this->savePaymentDetailsForAfterPayment($payment, $order, $ordertype);
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong, Please try again later!');
        }
        if ($saved) {
            \Session::put('success', 'Property Booking successful');
            return redirect()->to($order->getBookingPaymentSuccessDetailUrl());
        } else {
            return back()->withInput()->with('error', __('Something went wrong, Please try again later!'));
        }
    }

    public function savePaymentDetailsForAfterPayment($payment, $order, $ordertype)
    {
        try {
            $pay['type'] = $ordertype;
            $pay['type_id'] = $order['id'];
            $pay['payment_gateway'] = defaultPaymentGateway();
            $pay['transaction_id'] = $payment['id'];
            $pay['amount'] = $payment['amount'] / 100;
            $pay['currency'] = $payment['currency'];
            $pay['entity'] = $payment['entity'];
            $pay['order_id'] = $payment['order_id'];
            $pay['status'] = $payment['status'];
            $pay['method'] = $payment['method'];
            $pay['bank'] = $payment['bank'];
            $pay['wallet'] = $payment['wallet'];
            $pay['email'] = $payment['email'];
            $pay['contact'] = $payment['contact'];
            $pay['logs'] = json_encode((array) $payment);
            $pay['create_user'] = $order->user_id;
            $pay['ip_address'] = request()->ip();
            $bankTranstionId = isset($payment['acquirer_data']['bank_transaction_id']) ? $payment['acquirer_data']['bank_transaction_id'] : '';
            $pay['bank_transaction_id'] = $bankTranstionId;

            if ($orderPayment = $this->paymentClass::create($pay)) {
                $saved = true;
                if (isset($order->schedule_billing_data)) {
                    $order->schedule_billing_data = json_encode($orderPayment);
                }
                if ($ordertype == 'ScheduleVisit') {
                    $order->status = 'confirmed';
                    $order->payment_id = $orderPayment->id;
                    $order->save();
                    $visits = $this->ScheduleVisitProperty->where('schedule_visits_id', $order->id)->with(['property'])->get();
                    foreach ($visits as $visit) {
                        $vendor = $this->User->where('id', $visit->property->user_id)->first();
                        $booking = array(
                            'customer_id' => $order->customer->id,
                            'customer_name' => $order->customer->name,
                            'property_name' => $visit->property->property_name,
                            'slug' => $visit->slug,
                            'schedule_code' => $order->schedule_code,
                            'customer_image_path' => $order->customer->PicturePath,
                        );
                        $this->EmailNotificationsRepository->sendSchduleVisitBookingEmail($vendor, $booking);
                        $this->EmailNotificationsRepository->sendSchduleVisitBookingEmailUser($order, $booking);
                    }
                } else {
                    $order->agent_corp_points = calculateAgentCorporateRewardPoints($order['amount'], $order['code_type']);
                    $order->property_billing_data = json_encode($orderPayment);
                    $order->status = Booking::PENDING;
                    $order->payment_id = $orderPayment->id;
                    $order->save();
                    if ($order->code_type != '') {
                        $this->AgentCommisionEntryAfterPayment($order);
                    }
                    $this->EmailNotificationsRepository->sendPropertyBookingEmailVendor($order);
                    $this->EmailNotificationsRepository->sendPropertyBookingEmailUser($order);
                }
            }
        } catch (\Exception $e) {
            $saved = false;
        }
        return $saved;
    }

    public function orderIdGenerate($request)
    {
        $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
        //here request_id is ['booking_id' or 'schedule_visit_id']
        $bookingtype = $request->bookingtype ? $request->bookingtype : '';
        if ($bookingtype == 'Booking' || $bookingtype == 'ScheduleVisit') {
            $booking  = $this->$bookingtype->select('id', 'total')->where('id', $request->request_id)->first();
            if ($booking) {
                if ($booking->total < 1 && $bookingtype == 'ScheduleVisit') {
                    return response()->json(['type' => 'error', 'message' => 'First Please submit property details properly, The amount must be atleast INR 1.00']);
                }
                if ($booking->total < 1) {
                    return response()->json(['type' => 'error', 'message' => 'The order amount must be atleast INR 1.00']);
                }
                $order = $api->order->create(array('receipt' => $booking->id, 'amount' => $booking->total * 100, 'currency' => config('paymentsetting.currency'))); // Creates order
                // $order = $api->order->create(array('receipt' => $booking->id, 'amount' =>  100, 'currency' => config('paymentsetting.currency'))); // Creates order
                return response()->json(['order_id' => $order['id'], 'pay_amount' => $booking->total, 'bookingtype' => $bookingtype, 'type_id' => $booking->id]);
            } else {
                return response()->json(['error' => 'Something went wrong with this booking']);
            }
        } else {
            return response()->json(['error' => 'Something went wrong with this booking']);
        }
    }

    public function paymentWebhookResponse($request)
    {
        if (config('paymentsetting.razarpay_webhook_enable')) {
            $request = $request->all();
            $payment = $request['payload']['payment']['entity'];
            $saved = false;
            if (!empty($payment) && $payment['status'] == 'captured') {
                $ordertype = isset($payment['notes']['type']) ? $payment['notes']['type'] : '';
                $order_id = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
                $order = $this->$ordertype::where('id', $order_id)->first();
                if ($order->paymentCaptured) {
                    Log::info('Already payment captured successful');
                    return true;
                }
                if (!$order->payment) {
                    $saved = $this->savePaymentDetailsForAfterPayment($payment, $order, $ordertype);
                }
            } else {
                Log::info('Something went wrong, Please try again later!');
            }
            if ($saved) {
                Log::info('Property Booking successful');
            } else {
                Log::info('Something went wrong, Please try again later!');
            }
        } else {
            return true;
        }
    }

    public function makeBookingPaymentAPIWithRozarPayResponse($request)
    {
        if (config('paymentsetting.razarpay_webhook_enable')) {
            $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
            $payment = $api->payment->fetch($request->input('razorpay_payment_id'));
            $saved = false;
            if (!empty($payment) && $payment['status'] == 'captured') {
                $ordertype = isset($payment['notes']['type']) ? $payment['notes']['type'] : '';
                $order_id = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
                $order = $this->$ordertype::where('id', $order_id)->first();
                $saved = true;
            } else {
                return redirect()->back()->with('error', 'Something went wrong, Please try again later!');
            }
            if ($saved) {
                \Session::put('success', 'Property Booking successful');
                return redirect()->to($order->getBookingPaymentSuccessDetailUrl());
            } else {
                return back()->withInput()->with('error', __('Something went wrong, Please try again later!'));
            }
        } else {
            $api = new Api(config('paymentsetting.razorpay_api_key'), config('paymentsetting.seceret_key'));
            $payment = $api->payment->fetch($request->input('razorpay_payment_id'));
            $saved = false;
            if (!empty($payment) && $payment['status'] == 'captured') {
                $ordertype = isset($payment['notes']['type']) ? $payment['notes']['type'] : '';
                $order_id = isset($payment['notes']['type_id']) ? $payment['notes']['type_id'] : '';
                $order = $this->$ordertype::where('id', $order_id)->first();
                $saved = $this->savePaymentDetailsForAfterPayment($payment, $order, $ordertype);
            } else {
                return redirect()->back()->with('error', 'Something went wrong, Please try again later!');
            }
            if ($saved) {
                \Session::put('success', 'Property Booking successful');
                return redirect()->to($order->getBookingPaymentSuccessDetailUrl());
            } else {
                return back()->withInput()->with('error', __('Something went wrong, Please try again later!'));
            }
        }
    }

    public function AgentCommisionEntryAfterPayment($booking)
    {
        $wallet = new Wallet();
        $wallet->user_id = getAgentIdByAgentCode($booking->agent_corp_code);
        $wallet->booking_code = $booking->code;
        $wallet->booking_id = $booking->id;
        $wallet->type = 'credit';
        $wallet->amount = calculateAgentCorporateRewardPoints($booking->amount, $booking->code_type);
        $wallet->description = '';
        $wallet->created_at = now();
        if ($wallet->save()) {
            $this->EmailNotificationsRepository->sendNotificationAgentsForPointEarned($wallet);
        }
    }
}
