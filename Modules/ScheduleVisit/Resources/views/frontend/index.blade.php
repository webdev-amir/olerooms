@extends('layouts.app')
@section('title',"Schedule Visit".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
	<section class="bravo-list-tour padding50 bgDark m-0 bookingdetail_page">
		<div class="container">
			<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation">
				  <button class="nav-link active mr-3 {{ ($scheduleVisit->TotalProperty > 0)?"active":"" }}" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true"> <span>Visiting details  </span></button>
				</li>
				<li class="nav-item" role="presentation">
				  <button class="nav-link mr-3" {{ ($scheduleVisit->TotalProperty > 0)?"":"disabled" }} id="pills-payment-tab" data-toggle="pill" data-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment" aria-selected="false"> <span>Make Payment </span></button>
				</li>
				<li class="nav-item mr-3" role="presentation">
				  <button class="nav-link" disabled id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"> <span>Booking Confirmed </span></button>
				</li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
				<div class="tab-pane show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
				    @include('schedulevisit::frontend.includes.visiting_details')
				</div>
                @if($scheduleVisit->TotalProperty > 0)
					<div class="tab-pane" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
					  @include('schedulevisit::frontend.includes.visiting_payment')
					</div>
                @endif
			</div>
		</div>
    </section>
</div>
@endsection
@section('uniquePageScript')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
    $('#addPaymentButton').on('click', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "{{route('booking.orderIdGenerate')}}",
            data: $("#addPaymentForm").serialize(),
            beforeSend: function () {
                $("#loader_msg").html('Please wait payment initiate');
                $("#loader").show();
            },
            success: function (data) {
                $("#loader").hide();
                if (data['type'] == 'error') {
                  Lobibox.notify(data['type'], {
                    position: "top right",
                    msg: data['message']
                  });
                  return false;
                }
                var order_id = '';
                if (data.order_id) {
                    order_id = data.order_id;
                }
                var options = {
                    "key": "{{ config('paymentsetting.razorpay_api_key') }}", // Enter the Key ID generated from the Dashboard
                    "amount": data.pay_amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                    "currency": "{{ config('paymentsetting.currency') }}",
                    "name": "Schedule Booking",
                    "description": '',
                    //"image": "{{URL::to('img/logo.svg')}}",
                    "order_id": order_id, //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                    "handler": function (response) {
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
                rzp1.on('payment.failed', function (response) {

                });
                rzp1.open();
                $("#loader").show();
            },
        });
    });
</script>
@endsection