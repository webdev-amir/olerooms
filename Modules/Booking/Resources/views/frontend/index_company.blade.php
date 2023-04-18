@extends('layouts.app')
@section('title',"Booking ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content" id="bookingPageCompany">
    <section class="bravo-list-tour padding50 bgDark m-0 bookingdetail_page">
        <div class="container">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                @if(request()->route()->named('company.booking.details'))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active mr-3" id="pills-details-tab" data-toggle="pill" data-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true"> <span>Booking Details </span></button>
                    </li>
                    
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mr-3" id="pills-bookingpayment-tab" type="button" aria-selected="false"> <span>Make Payment </span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" type="button" aria-selected="false"> <span>Booking Confirmed </span></button>
                    </li>
                @else
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  mr-3" id="pills-details-tab" data-toggle="pill" data-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true"> <span>Booking Details </span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link show active mr-3" id="pills-bookingpayment-tab" data-toggle="pill" data-target="#pills-bookingpayment" type="button" role="tab" aria-controls="pills-bookingpayment" aria-selected="true"> <span>Make Payment </span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" type="button" aria-selected="false"> <span>Booking Confirmed </span></button>
                    </li>
                @endif
            </ul>
            <div class="tab-content mT30" id="pills-tabContent ">
                @if(request()->route()->named('company.booking.details'))
                    <div class="tab-pane show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
                        @include('booking::frontend.includes.company.booking_details')
                    </div>
                @else
                    <div class="tab-pane" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
                        @include('booking::frontend.includes.company.booking_details_edit')
                    </div>
                    <div class="tab-pane  show active" id="pills-bookingpayment" role="tabpanel" aria-labelledby="pills-bookingpayment-tab">
                        @include('booking::frontend.includes.company.booking_payment')
                    </div>
                @endif
                <div class="tab-pane" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('uniquePageScript')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
    $('#addPaymentButton').on('click', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{route('company.booking.orderIdGenerate')}}",
            data: $("#addPaymentForm").serialize(),
            beforeSend: function() {
                $("#loader_msg").html('Please wait payment initiate');
                $("#loader").show();
            },
            success: function(data) {
                $("#loader").hide();
                var order_id = '';
                if (data.order_id) {
                    order_id = data.order_id;
                }
                var options = {
                    "key": "{{ config('paymentsetting.razorpay_api_key') }}", // Enter the Key ID generated from the Dashboard
                    "amount": data.pay_amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                    "currency": "{{ config('paymentsetting.currency') }}",
                    "name": "Booking Payment",
                    "description": '',
                    //"image": "{{URL::to('img/logo.svg')}}",
                    "order_id": order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                    "handler": function(response) {
                        $('#razorpay_payment_id').val(response.razorpay_payment_id);
                        $('#razorpay_order_id').val(response.razorpay_order_id);
                        $('#razorpay_signature').val(response.razorpay_signature);
                        $('#addPaymentForm').submit();
                    },
                    "prefill": {
                        "name": "{{ auth()->user()->name }}",
                        "email": "{{ auth()->user()->email }}",
                        "contact": "{{ auth()->user()->phone }}"
                    },
                    "notes": {
                        "type": data.bookingtype,
                        "type_id": data.type_id,
                    },
                    "theme": {
                        "color": "#3399cc"
                    },
                    "modal": {
                        "ondismiss": function(){
                            $("#loader").hide();
                        }
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function(response) {
                });
                rzp1.open();
                $("#loader").show();
            },
        });
    });
</script>
@endsection