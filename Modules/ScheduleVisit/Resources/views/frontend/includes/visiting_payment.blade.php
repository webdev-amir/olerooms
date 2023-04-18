<div class="row justify-content-center">
    <div class="col-sm-12 col-md-12 col-lg-8">
        <form action="{{ route('booking.payment') }}" method="post" id="addPaymentForm">
        @csrf
            <div class="rightsideInfo">
                <div class="sidewidget">
                    <h4 class="font20 medium black allover_padding mb-0"> Payment Summary </h4>
                </div>
                <div class="sidewidget border-0">
                    <div class="allover_padding">
                        <div class="form-group floating-field">
                            <input type="hidden" name="razorpay_payment_id" value="" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_order_id" value="" id="razorpay_order_id">
                            <input type="hidden" name="razorpay_signature" value="" id="razorpay_signature">
                            <input type="hidden" name="generated_signature" value="" id="generated_signature">
                            <input type="hidden" name="amount" value="{{setting_item('schedule_visit_amount')}}" id="pay_amount">
                            <input type="hidden" name="request_id" value="{{$scheduleVisit->id}}" id="visit_id">
                            <input type="hidden" name="bookingtype" value="ScheduleVisit" id="bookingtype">
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
							<div class="black regular font18">Payable Amount</div>
							<div class="font20 green greenshade1 semibold"> {{numberformatWithCurrency(setting_item('schedule_visit_amount'))}}</div>
						</div>
                    </div>
                </div>
            </div>
            <button class="btn btn-success btndefault customBtn mt-4 mb-4 w-100" type="button" id="addPaymentButton">Make Payment of &nbsp;{{numberformatWithCurrency(setting_item('schedule_visit_amount'))}} <i class="ri-arrow-right-line"></i></button>
        </form>
    </div>
</div>