@extends('layouts.app')
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-5 medium">
                        Enter <span class="green">OTP Verification!</span>
                        <p class="simplified-sub mt-2"> Please enter the 4 digit code send to your phone </p>
                    </div>
                    <div class="customForm">
                        <form method="POST" class="directSubmit" action="{{route('customer.MobileLoginOTPVerify')}}" id="F_cotpverify">
                            @csrf
                            <input type="hidden" name="redirect" value="{{session('redirect_url')}}" id="redirect">

                            <div class="form-group mT30 otpRow d-flex verification-code ">
                                <input type="text" maxlength=1 placeholder="-" class="form-control numberonly">
                                <input type="text" maxlength=1 placeholder="-" class="form-control numberonly">
                                <input type="text" maxlength=1 placeholder="-" class="form-control numberonly">
                                <input type="text" maxlength=1 placeholder="-" class="form-control numberonly">
                            </div>
                            <div class="form-group ermsg">
                                <input type="hidden" name="mobile_otp" id="mobile_otp" class="form-control" required>
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center mb-0 mT30 ermsg">
                                <a href="{{route('resend.otp')}}" class="mb-0 font18 medium grey resend-mobile-otp">Resend OTP?</a>
                                <button type="submit" id="cotpverify" class="btn customBtn btn-success minw-184 form-submit directSubmit" data-loader="Please wait, Verifying your OTP."> Submit OTP </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Enter <span class="d-block black">OTP Verification! </span></h4>
                            <p>Please enter the 4 digit code send to your phone </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection