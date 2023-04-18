@extends('layouts.app')
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-4 medium">
                        Create<span class="green"> New Password?</span>
                        <p class="simplified-sub mt-2"> </p>
                    </div>
                    <div class="customForm">
                        {!! Form::open(['route' => checkRoleUsingSegment().'.password.update','class'=>'form form-loader','id'=>'F_resetPass','autocomplete'=> 'off']) !!}
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group ermsg">
                            <!-- <label class="font16 grey">Your new password must be different from previously used password.</label> -->
                            {{ Form::text('email',$email??old('email'), ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email ID','title'=>'Please enter email address','maxlength'=>'150']) }}
                        </div>
                        <div class="form-group ermsg input_icon">
                            <i class="fa fa-eye toggle-eye-password" aria-hidden="true"></i>
                            <input type="password" required id="newpassword" placeholder="New Password" name="password" class="form-control" title="Please enter password" autocomplete="new-password">
                        </div>
                        <div class="form-group ermsg input_icon">
                            <i class="fa fa-eye toggle-eye-password" aria-hidden="true"></i>
                            <input type="password" required placeholder="Confirm Password" name="password_confirmation" class="form-control" title="Please enter confirm password" id="password-confirm" autocomplete="new-password">
                        </div>
                        <div class="form-group text-right mb-0 mt-4">
                            <button type="submit" id="resetPass" class="btn customBtn btn-success minw-184 form-submit directSubmit"> Continue </button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4">Create <span class="d-block black">New Password?
                                </span></h4>
                            <p>Your new password must be 8 digit password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection