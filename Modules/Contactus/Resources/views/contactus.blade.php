@extends('layouts.app')
@section('title',trans('menu.contactus')." ".trans('menu.pipe')." " .app_name())
@section('content')
    <div class="page-template-content">
			<section class="innerbanner contactus-olerooms">
				<div class="container text-center">
					<h1>Have Any Doubts?</h1>
					<span class="subheading d-block">{!! $pageInfo->banner_heading !!}</span>
				</div>
			</section>
			<section class="bravo-list-hotel bgDark m-0 padding50 ">
				<div class="container">
				{!! Form::open(['route' => 'contactus.store','class'=>'common_form full-form','id'=>'F_contactUs','autocomplete'=> 'off']) !!}
					 <div class="row">
						<div class="col-sm-12 col-md-12 col-lg-8 fadeInUp animated1 selected">
							<div class="customForm formouterDesign shadow-none">
								<div class="title mt-0">Contact Us</div>
								<div class="row">
									<div class="col-sm-12 col-md-6">
										<div class="form-group ermsg">
											<label class="font16 grey">Name</label>
											{{ Form::text('first_name',null, ['required','class'=>'form-control','id'=>'first_name','placeholder'=>'Name','title'=>trans('menu.validiation.please_enter_name'),'maxlength'=>'50']) }}
										</div>
									</div>
									<div class="col-sm-12 col-md-6">
										<div class="form-group ermsg">
											<label class="font16 grey">Email ID</label>
											{{ Form::email('email',null, ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email ID','title'=>'Please enter your email address','data-msg-email'=>'Please enter your valid email address','maxlength'=>'100','autocomplete'=>"email"]) }}
											
										</div>
									</div>
									<div class="col-sm-12 col-md-12">
										<label class="font16 grey">Mobile Number</label>
										<div class="form-group ermsg">
											<input type="text" placeholder="Mobile Number" name="phone" class="form-control numberonly" required autocomplete="off" title="Please enter phone number" maxlength="10">
										</div>
									</div>
									<div class="col-sm-12 col-md-12">
										<label class="font16 grey">Message</label>
										<div class="form-group ermsg">
										{{ Form::textarea('message',null, ['cols'=>'20','rows'=>"6",'required','class'=>'form-control','id'=>'message','title'=>'Please enter your message','placeholder'=>'Message']) }}
										</div>
									</div>
								</div>
								<div class="form-group text-left mb-0">
									<button type="submit" id="contactUs" class="btn customBtn btn-success minw-184 directSubmit" data-loader="@lang('flash.loader.sending_your_contactus_request')">Send </button>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-4">
							<div class="authContent w-100 min-height100 h-100">
								<div class="fadeInUp animated1 selected">
									<h4 class="green mb-4"><span class="d-block black"> Reach us at </span></h4>
									<ul class="contactInfo">
										<li><i class="ri-mail-fill"></i> <a href="mailto:{!! $configVariables['adminemail']['value'] !!}" class="__cf_email__ contact_us_mail">{!! $configVariables['adminemail']['value'] !!}</a></li>
										<li><i class="ri-phone-fill"></i><a href="tel:{!! $configVariables['admincontact']['value'] !!}" class="contact_us_phone"> {!! $configVariables['admincontact']['value'] !!}</a></li>
										
										<li><i class="ri-whatsapp-fill"></i><a href="https://wa.me/{!! $configVariables['whatsapp']['value'] !!}" class="contact_us_phone" target="_blank"> {!! $configVariables['whatsapp']['value'] !!}</a></li>
										<li><i class="ri-map-pin-fill"></i> {!! $configVariables['address']['value'] !!}  </li>
									</ul>
								</div>
							 </div>
						</div>
					 </div>
					 {!! Form::close() !!}
					 <div class="mapSec mT50">
					 <iframe src="{!! $configVariables['mapaddress']['value'] !!}" width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
					</div>
				</div>
		</section>
	</div>
@endsection
@section('uniquePageScript')
<!-- {!! NoCaptcha::renderJs() !!} -->
@endsection