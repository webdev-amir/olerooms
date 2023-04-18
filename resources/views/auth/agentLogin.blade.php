@extends('agent::layouts.master')
@section('title','Agent Login | '.config('app.name'))
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-5 medium">
                        Login To <span class="green">Get Started!</span>
                        <p class="simplified-sub mt-2"> Login to your Account </p>
                    </div>
                    <div class="tab-content" id="myTabContent">
                                <form method="POST"  action="{{route('agent.login')}}" id="F_loginAgent" autocomplete="off">
                                @csrf
                                <input type="hidden" name="redirect" value="{{url()->previous()}}" id="redirect">
                                <div class="customForm mT30">
                                    <div class="error message-error invalid-feedback"></div>
                                    <div class="form-group ermsg">
                                        <input required type="email" value="" placeholder="Email ID" name="email" class="form-control" title="Enter email to login.">
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                    <div class="form-group ermsg">
                                        <input required type="password" value="" placeholder="Password" name="password" class="form-control error-password" title="Password is required field">
                                        <span class="invalid-feedback error error-password"></span>
                                    </div>
                                    <div class="form-group mt-4">
                                        <div class="d-flex justify-content-between">
                                            <label for="remember-me" class="mb0 remembertext">
                                                <input type="checkbox" name="remember" id="remember-me" value="1"> Remember Password </a>
                                                <span class="checkmark fcheckbox"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group text-right mb-0  mt-4 d-flex justify-content-between align-items-center">
                                        <a href="{{ route('agent.password.request') }}" class="grey font16 regular text-decoration-underline"> Forgot Password? </a>
                                        <button type="submit" id="loginAgent" class="btn customBtn btn-success minw-101 form-submit directSubmit gradientBtn" data-loader="Please wait, Logging to your account."> Login </button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Login to <span class="d-block black">Get Started! </span></h4>
                            <p>Enter Registered Email to Login</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection