@extends('layouts.app')
@section('content')
<div class="page-template-content">
    <section class=" bravo-list-tour authSection padding50">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
                    <div class="heading-title mb-4 medium">
                       Forgot Your<span class="green"> Password?</span>
                        <p class="simplified-sub mt-2"> </p>
                    </div>
                    @if(request()->segment(1) == 'customer')
                        @php $resetRoute = 'customer.password.email' @endphp
                    @elseif(request()->segment(1) == 'agent')
                        @php $resetRoute = 'agent.password.email' @endphp
                    @elseif(request()->segment(1) == 'company')
                        @php $resetRoute = 'company.password.email' @endphp
                    @elseif(request()->segment(1) == 'vendor')
                        @php $resetRoute = 'vendor.password.email' @endphp
                    @else
                        @php $resetRoute = 'customer.password.email' @endphp
                    @endif
                    <div class="customForm">
                        {!! Form::open(['route' => $resetRoute,'class'=>'form form-loader','id'=>'F_forgotPass','autocomplete'=> 'off']) !!}
                        {{ Form::hidden('rolename',checkRoleUsingSegment(), ['required']) }}
                            <div class="form-group ermsg">
                                <label class="font16 grey">Enter your email and we will send you instructions to reset your password.</label>
                                {{ Form::text('email',old('email'), ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email ID','title'=>'Please enter email address','maxlength'=>'150']) }}
                            </div>
                            <div class="form-group text-right mb-0 mt-4">
                                <button type="submit" id="forgotPass" class="btn customBtn btn-success minw-184 form-submit directSubmit"> Continue </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="authContent h-100">
                        <div class="fadeInUp animated1 selected">
                            <h4 class="green mb-4"> Forgot Your  <span class="d-block black">Password?
                                </span></h4>
                            <p> Enter your email and we will send you instructions to reset your password.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection