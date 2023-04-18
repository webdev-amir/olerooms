@extends('propertyownerdashboard::layouts.master')
@section('title', "Login ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container"> 
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-5 medium">
                        Login To <span class="green">Get Started!</span>
                        <p class="simplified-sub mt-2"> Login to your account </p>
                    </div>
                    <div class="customForm">
                        <form method="POST" action="{{route('vendor.login.mobile')}}" id="F_loginVendor" autocomplete="off">
                            <div class="form-group ermsg">
                                <input type="text" placeholder="Mobile Number" name="phone" class="form-control numberonly vendorPhone" required autocomplete="off" title="Please enter phone number" maxlength="10">
                            </div>
                            <div class="form-group text-right mb-0 mT30">
                                <button type="submit" class="btn customBtn  btn-success minw-184 form-submit directSubmit gradientBtn" id="loginVendor" data-loader="Please wait, Logging to your account"> Login</button>
                            </div>
                        </form>
                        <form method="POST" style="display: none;" class="vendor_OTP_form" action="{{route('vendor.MobileVerify.OTP')}}" id="F_votpverify">
                            @csrf
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
                                <a href="{{route('vendor.resend.otp')}}" class="mb-0 font18 medium grey resend-mobile-otp">Resend OTP?</a>
                                <button type="submit" id="votpverify" class="btn customBtn btn-success minw-184 form-submit directSubmit gradientBtn" data-loader="Please wait, Verifying your Otp."> Submit OTP </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Login to <span class="d-block black">Get Started! </span></h4>
                            <p>Enter Registered mobile number</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection