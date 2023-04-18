@extends('layouts.app')
@section('title', "Register ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-4 medium">
                        Welcome to<span class="green"> OLE Rooms!</span>
                        <p class="simplified-sub mt-2"> Please complete your personal information </p>
                    </div>
                    <div class="customForm">
                        {!! Form::open(['route' => 'register','class'=>'form form-loader','id'=>'F_cregister','autocomplete'=> 'off']) !!}
                        <div class="form-group ermsg">
                            {{ Form::text('name',old('name'), ['required','class'=>'form-control','id'=>'name','placeholder'=>'Your Full Name','title'=>'Please enter full name','maxlength'=>'50']) }}
                        </div>
                        <div class="form-group ermsg">
                            {{ Form::email('email',old('email'), ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email Address','title'=>'Please enter email address','maxlength'=>'150']) }}
                        </div>
                        <div class="form-group ermsg">
                            {{ Form::text('phone',old('phone'), ['maxlength'=>'10','required','class'=>'form-control isinteger','id'=>'phone','placeholder'=>'Mobile Number','title'=>'Please enter phone number']) }}
                        </div>
                        <div class="form-group ermsg input_icon">
                            <i class="fa fa-eye toggle-eye-password" aria-hidden="true"></i>
                            <input type="password" required id="newpassword" placeholder="Password" name="password" class="form-control" title="Please enter password" autocomplete="new-password">
                        </div>
                        <div class="form-group ermsg input_icon">
                            <i class="fa fa-eye toggle-eye-password" aria-hidden="true"></i>
                            <input type="password" required placeholder="Confirm Password" name="password_confirmation" class="form-control" title="Please enter confirm password" id="password-confirm" autocomplete="new-password">
                        </div>
                        <div class="form-group ermsg mt-4">
                            <div class="d-flex justify-content-between">
                                <label for="remember-me" class="mb0 remembertext">
                                    <input type="checkbox" required name="term" value="1"> I have
                                    read &amp; agreed <a href="{{route('pages.show','terms-and-conditions')}}" target='_blank' class="green text-decoration-underline">Terms
                                        &amp; Conditions.</a>
                                    <span class="checkmark fcheckbox"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group text-right mb-0 mt-4">
                            <button type="submit" id="cregister" class="btn customBtn btn-success minw-184 form-submit directSubmit"> Register </button>
                        </div>
                        <div class="form-group mt-4">
                            <p class="mb-0 font18 medium grey">Already have an account? <a href="{{route('customer.login')}}" class="green">Login</a> </p>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent h-100">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Welcome to <span class="d-block black">OLE Rooms!
                                </span></h4>
                            <p> Please complete your personal information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection