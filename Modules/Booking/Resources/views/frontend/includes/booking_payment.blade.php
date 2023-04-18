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
                            <input type="hidden" name="amount" value="{{$booking->total}}" id="pay_amount">
                            <input type="hidden" name="request_id" value="{{$booking->id}}" id="booking_id">
                            <input type="hidden" name="bookingtype" value="Booking" id="bookingtype">
                        </div>



                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Guest Details</div>
                            <div class="font18 grey medium totalGuests">0</div>
                        </div>
                        @if(in_array($booking->Property->propertyType->slug,['guest-hotel','hostel-pg-one-day','hostel-pg']))

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Per Seat Amount</div>
                            <div class="font18 grey medium selectedPerRoomAmount">0</div>
                        </div>
                        @endif
                        @if(in_array($booking->Property->propertyType->slug,['guest-hotel','hostel-pg-one-day']))
                        <div class="d-flex align-items-center justify-content-between mb-3 daysDiffernceDiv">
                            <div class="black regular font18">Total Days</div>
                            <div class="font18 grey medium daysDiffernce">0</div>
                        </div>
                        @endif
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Total Amount</div>
                            <div class="font18 grey medium totalAmount">{{numberformatWithCurrency(0)}}</div>
                        </div>

                        @if($bookingJsonData->discount > 0)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Discount</div>
                            <div class="font18 grey medium">- <span class="discountAmount green"> {{numberformatWithCurrency($bookingJsonData->discount)}}</span></div>
                        </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Final Amount</div>
                            <div class="font20 green greenshade1 semibold finalPayableAmount ">{{numberformatWithCurrency(0)}}</div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between remainingAmountDiv" @if($booking->remaining_payable_amount > 0) @else style="display:none !important;" @endif>
                            <div class="black regular font18">Remaining Amount</div>
                            <div class="font20 red greenshade1 semibold">- <span class="remainingAmount">
                                    {{numberformatWithCurrency($booking->remaining_payable_amount)}}
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="black regular font18">Payabale Amount</div>
                            <div class="font20 orange semibold finalPayableAmount finalAmountAfterSelection">{{numberformatWithCurrency(0)}}</div>
                        </div>

                    </div>
                </div>
            </div>
            <button class="btn btn-success btndefault customBtn w-100 mt-4 mb-4" type="button" id="addPaymentButton">Make Payment of &nbsp; <span class="finalPayableAmount finalAmountAfterSelection"> {{numberformatWithCurrency(0)}}</span>
                <i class="ri-arrow-right-line"></i>
            </button>
        </form>
    </div>
</div>