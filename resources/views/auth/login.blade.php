@extends('layouts.app')
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
                    <ul class="nav nav-tabs authTab border-0" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Mobile</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">Email</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="customForm mT30">
                                <div class="message-error"></div>
                                <form method="POST" id="F_loginCustomer" autocomplete="off" action="{{route('customer.login.mobile')}}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{url()->previous()}}" id="redirect">
                                    <div class="form-group">
                                        <input type="text" value="" placeholder="Mobile Number" name="phone" class="form-control numberonly" maxlength="10">
                                        <span class="invalid-feedback error error-phone"></span>
                                    </div>
                                    <div class="error message-error invalid-feedback"></div>
                                    <div class="form-group text-right mb-0  mt-5">
                                        <button type="submit" class="btn customBtn btn-success minw-101 directSubmit" id="loginCustomer"> Login </button>
                                    </div>
                                    <div class="form-group mt-5">
                                        <p class="mb-3 font18 medium grey">Not a Register Member? <a href="{{route('register')}}" class="green">Sign Up</a></p>

                                        <p class="mb-0 font18 medium grey strong">Are you a property owner? Click link to login as <a href="{{route('vendor.login')}}" class="red">Property Owner</a>.</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form method="POST" class="bravo-form-login">
                                @csrf
                                <input type="hidden" name="redirect" value="{{url()->previous()}}" id="redirect">
                                <div class="customForm mT30">
                                    <div class="error message-error invalid-feedback"></div>
                                    <div class="form-group">
                                        <input type="text" value="" placeholder="Email ID" name="email" class="form-control ">
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                    <div class="form-group input_icon">
                                        <i class="fa fa-eye toggle-eye-password" aria-hidden="true"></i>
                                        <input type="password" value="" placeholder="Password" name="password" id="password" class="form-control error-password">
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
                                        <a href="{{ route('customer.password.request') }}" class="grey font16 regular text-decoration-underline"> Forgot Password? </a>
                                        <button type="submit" class="btn customBtn btn-success minw-101"> Login </button>
                                    </div>
                                    <div class="form-group mt-4">
                                        <p class="mb-0 font18 medium grey">Not a Register Member? <a href="{{route('register')}}" class="green">Sign Up</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Login to <span class="d-block black">Get Started! </span></h4>
                            <p>Enter Registered mobile or Email to Login</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection