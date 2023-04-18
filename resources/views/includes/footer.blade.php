<div class="page-template-content">
	<div class="footerOuter">
		<div class="container">
			<div class="bravo-call-to-action style_2 exploreNow">
				<div class="container">
				  <div class="context">
					<div class="row align-items-center no-gutters">
					  <div class="col-md-10">
						<div class="sub_title">
							Write to us in case of queries.
						</div>
					  </div>
					  <div class="col-md-2">
						<a class="btn-more" href="{{route('contactus.create')}}" title="Contact Us" alt="Contact Us"> Contact Us </a>
					  </div>
					</div>
				  </div>
				</div>
			</div>
		</div>
		<section class="bravo_footer fadeInUp animated2 delay1">
			<div class="main-footer">
				<div class="container">
					<h2 class="fotter-txt">How can we help?</h2>
					<div class="row">
						<div class="col-lg-3 col-md-6">
							<div class="nav-footer">
								<div class="context footer-nav">
									<h5>Rent Solutions</h5>
									<ul>
										@if(count($propertyTypes)>0)
											@foreach($propertyTypes as $flink)
											<li>
												<a href="{{route('search')}}?property_type={{$flink->id}}">{{ucfirst($flink->name)}}</a>
											</li>
											@endforeach
										@endif
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="nav-footer">
								<div class="context footer-nav">
									<h5>Ole Rooms Docs</h5>
									<ul>
										<li><a href="{{route('pages.show','terms-and-conditions')}}">Terms & Conditions</a></li>
										<li><a href="{{route('pages.show','cancellation-policy')}}">Cancellation Policy</a></li>
										<li><a href="{{route('pages.show','privacy-policy')}}">Privacy Policy</a></li>
										<li><a href="{{route('frontend.news')}}">News</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="nav-footer">
								<div class="context footer-nav">
									<h5>Imperial Stay Pvt.Ltd </h5>
									<ul>
										<li><a href="{{route('pages.show','about-ole-rooms')}}">About OLE Rooms</a></li>
										<li><a href="{{route('pages.show','life-at-ole-rooms')}}">Life at OLE Rooms(Jobs)</a></li>
										<li><a href="{{route('frontend.faq')}}">FAQ</a></li>
										<li><a href="{{route('contactus.create')}}">Contact Us</a></li>
									</ul>
								</div>
							</div>
						</div> 
						<div class="col-lg-3 col-md-6">
							<div class="nav-footer">
								<div class="context footer-nav">
									<h5>Contact Us</h5>
									<div class="contact">
										<div class="c-title">
											<i class="ri-phone-fill"></i>
										</div>
										<div class="sub"> 
											<a href="tel:{!! @$socialLinkData['admincontact']['value'] !!}">{!! @$socialLinkData['admincontact']['value'] !!}</a> 
											<!-- <a href="tel:{!! @$socialLinkData['admincontact2']['value'] !!}">{!! @$socialLinkData['admincontact2']['value'] !!}</a> -->
										</div>
									</div>
									<div class="contact">
										<div class="c-title">
											<i class="ri-whatsapp-fill"></i>
										</div>
										<div class="sub"> <a href="tel:{!! @$socialLinkData['whatsapp']['value'] !!}">{!! @$socialLinkData['whatsapp']['value'] !!}</a> </div>
									</div>
									<div class="contact">
										<div class="c-title"> 
											<i class="ri-mail-fill"></i>
										</div>
										<div class="sub">
											<a href="mailto:{!! @$socialLinkData['adminemail']['value'] !!}" class="__cf_email__">
												{!! @$socialLinkData['adminemail']['value'] !!}
											</a>
										</div>
									</div>
									<div class="contact footer-nav d-block download_app_parent">
										<h5> Download App </h5>
										<div class="d-flex download_app">
											<a href="{!! @$configVariables['ios-app-url']['value'] !!}" target="_blank"><img src="{{URL::to('images/apple.svg')}}" alt="IOS App"/></a>
											<a href="{!! @$configVariables['android-app-url']['value'] !!}" target="_blank"><img src="{{URL::to('images/android.svg')}}" alt="Android App"/></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section>
			<div class="copy-right">
				<div class="container context">
					<div class="row align-items-center">
						<div class="col-sm-12 col-md-6 copy-text-line"> Copyright © {{date('Y')}} All Rights Reserved - {{config::get('app.name')}} Inc. </div>
						<div class="col-sm-12 col-md-6">
							<div class="sub social-footer text-right">
								<a href="{!! @$socialLinkData['facebook']['value'] !!}">
									<i class="ri-facebook-circle-fill"></i>
								</a>
								<a href="{!! @$socialLinkData['twitter']['value'] !!}">
									<i class="ri-twitter-fill"></i>
								</a>
								<a href="{!! @$socialLinkData['instagram']['value'] !!}">
									<i class="ri-instagram-fill"></i>
								</a>
								<a href="{!! @$socialLinkData['linkedin']['value'] !!}">
									<i class="ri-linkedin-box-fill"></i>
								</a>
								<a href="{!! @$socialLinkData['youtube']['value'] !!}">
									<i class="ri-youtube-fill"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
