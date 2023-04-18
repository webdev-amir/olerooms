@extends('layouts.app')
@section('title',"Schedule Visit".trans('menu.pipe')." " .app_name())
@section('head')
<link rel="stylesheet" type="text/css" href="{{URL::to('theme/libs/fotorama/fotorama.css')}}">
@endsection
@section('content')
<div class="page-template-content" id="schedule_visit_success">
	<section class="bravo-list-tour padding50 bgDark m-0 bookingdetail_page">
		<div class="container">
			<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link mr-3 active"> <span>Visiting details </span></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link mr-3 active"> <span>Make Payment </span></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link active"> <span>Booking Confirmed </span></button>
				</li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
				<div class="tab-pane active">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="leftside_data bookingconfirm_tab">
								<div class="booking_confirm">
									<figure class="mb-0 text-center">
										<img src="{{ URL::to('images/bookingconfirm.svg') }}" alt="image not found" />
									</figure>
									<p class="font34 greendark medium mT30 text-center">Your Schedule Visit Booking<br>
										<span class="green"></span> Successfully Done!
									</p>
								</div>
								<div class="printRow d-flex justify-content-between align-items-center mb-4">
									<span class="green font20 regular">Property info</span>
									<a href="#" class="btnprn"><img src="{{ URL::to('images/printicon.svg') }}" alt="image not found" /></a>
								</div>
								<div class="accordion accordionCustom" id="accordionExample">
									<div class="visit-map">
										@foreach($scheduleVisit->scheduleVisitProperty as $key => $val)
										<div class="card mb-3 border-bottom1 visit-map-child">
											<div class="card-header p-0" id="bookingconfirm{{$key+1}}_{{$key+1}}">
												<h2 class="mb-0 lineHeight0">
													<button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#bookingconfirm{{$key+1}}" aria-expanded="false" aria-controls="bookingconfirm{{$key+1}}">
														<span class="create-span">{{$key+1}}</span>{{$val->property->property_code}}
													</button>
												</h2>
											</div>
											<div id="bookingconfirm{{$key+1}}" class="collapse show" aria-labelledby="bookingconfirm{{$key+1}}_{{$key+1}}">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-12 col-md-12 col-lg-6">
															<div class="mapSec" data-id="{{$key+1}}">
																<div class="w-100 native-content" id="embedMap_{{$key+1}}" style="width: 400px; height: 300px;">
																</div>

																<input type="hidden" id="lat_{{$key+1}}" value="{{$val->property->lat}}" />
																<input type="hidden" id="long_{{$key+1}}" value="{{$val->property->long}}" />
																<input type="hidden" id="map_location_{{$key+1}}" value="{{$val->property->map_location}}" />
															</div>

														</div>
														<div class="col-sm-12 col-md-12 col-lg-6 pr-0">
															<div>
																<div class="visit-new-add d-flex">
																	<figure class="mr-3" style="background-image: url('{{ $val->property->CoverImgThunbnail}}'),url('{{onerrorReturnImage()}}') ;">
																	</figure>
																	<div class="complete-detail pl-0">
																		<div class="details-list-name">
																			<h4 class="font20 medium black turnicate1">{{$val->property->property_code}}</h4>
																			<div class="rating_star detail-star-lists px-0">
																				<div class="myratingview" data-rating="{{$val->property->RatingAverage}}">

																				</div>
																			</div>
																		</div>
																		<div class="click-tag mt-2">
																			@php
																			$availableForFilter = config('custom.property_available_for');
																			@endphp
																			@if(!empty($val->property->propertyAvailableFor))
																			<p>
																				<a>
																					<img src="{{URL::to('images/hotel-icon.svg')}}" class="details-img">
																					@foreach($val->property->propertyAvailableFor as $propertyAvailableFor)
																					{{$availableForFilter[$propertyAvailableFor->available_for]}}
																					@if($loop->iteration != $loop->last),@endif
																					@endforeach
																				</a>
																			</p>
																			@endif
																			@if($val->property->furnished_type)
																			<p><a><img src="{{ URL::to('images/sleep.svg') }}" class="details-img">{{ucfirst($val->property->furnished_type)}}</a></p>
																			@endif
																			@if($val->property->total_seats)
																			<p><a><img src="{{ URL::to('images/seat.svg') }}" class="details-img">{{$val->property->total_seats}}</a></p>
																			@endif
																		</div>
																		<div class="checking d-block">
																			<div class="date-in mb-2">
																				<p class="grey font14">Visiting Date</p>
																				<p class="book-confrom font16 black medium"> {{ date('l, jS F, Y',strtotime($val->visit_date))}}</p>
																			</div>
																			<div class="date-in mb-2">
																				<p class="grey font14">Visiting Time</p>
																				<p class="book-confrom font16 black medium">{{ date('h:i A', strtotime($val->visit_time))}}</p>
																			</div>
																		</div>
																	</div>
																</div>
																<div>
																	<p class="mb-2 grey">
																		<img class="mr-2" src="{{ URL::to('images/map-icon.svg') }}" alt="grid">
																		{{$val->property->map_location}}
																	</p>
																	<div class="owner-confilg d-flex mt-3">
																		<div><i class="ri-information-fill font35 mr-2 green"></i></div>
																		<div>
																			<p class="black">Convinient time to visit property:</p>
																			<p class="grey">Owner will be available on {{$val->property->convenient_time}}</p>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										@endforeach
									</div>
									<div class="card mb-3 border-bottom1">
										<div class="card-header p-0" id="bookingconfirm1_four">
											<h2 class="mb-0 lineHeight0">
												<button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#bookingconfirm4" aria-expanded="true" aria-controls="bookingconfirm4">
													Customer Detail
												</button>
											</h2>
										</div>
										<div id="bookingconfirm4" class="collapse show" aria-labelledby="bookingconfirm1_four">
											<div class="open-tag border-0 bgDark">
												<div class="borderRound p-3">
													<form>
														<!--mobile view-->
														<div class="form-row d-flex d-lg-none mobileView_dashdata">
															<div class="col-12 mb-3">
																<img src="{{$scheduleVisit->customer->PicturePath}}" alt="proimg" onerror="this.src='{{onerrorProImage()}}'" width="100px" height="100px" />
															</div>
															<div class="col-md-6 mobileInline_6">
																<div class="col">
																	<div class="form-group td-tag d-flex justify-content-between align-items-start">
																		<span>Name</span>
																		<span class="turnicate1 grey w-50 text-left"> {{auth()->user()->name}}</span>
																	</div>
																	<div class="form-group td-tag d-flex justify-content-between align-items-start">
																		<span>Email</span>
																		<span class="grey w-50 text-left"> {{auth()->user()->email}} </span>
																	</div>
																</div>
															</div>
														</div>
														<!--mobile view end-->

														<div class="form-row d-none d-lg-flex">
															<div class="col-md-12 right-border ntr">
																<div class="col">
																	<img src="{{$scheduleVisit->customer->PicturePath}}" alt="proimg" onerror="this.src='{{onerrorProImage()}}'" width="100px" />
																</div>
																<div class="col">
																	<div class="form-group td-tag">
																		Name
																	</div>
																	<div class="form-group td-tag">
																		Email
																	</div>
																</div>
																<div class="col">
																	<div class="form-group bio-tag">
																		{{auth()->user()->name}}
																	</div>
																	<div class="form-group bio-tag">
																		{{auth()->user()->email}}
																	</div>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-bottom1 mb-3">
										<div class="card-header p-0" id="bookingconfirm1_five">
											<h2 class="mb-0 lineHeight0">
												<button class="btn-link border-0 bg-transparent d-flex align-items-center	" type="button" data-toggle="collapse" data-target="#bookingconfirm5" aria-expanded="true" aria-controls="bookingconfirm5">
													Transaction Details
												</button>
											</h2>
										</div>
										<div id="bookingconfirm5" class="collapse show" aria-labelledby="bookingconfirm1_five">
											<div class="open-tag border-0 bgDark">
												<div class="collapse show" id="collapseExample">
													<div class="card card-body">
														<div class="">
															<form>
																<!-- mobile view -->
																<div class="form-row d-flex d-lg-none mobileView_dashdata">
																	<div class="col-md-6 mobileInline_6">
																		<div class="col">
																			<div class="form-group td-tag d-flex justify-content-between align-items-start">
																				<span>Transaction ID</span>
																				<span class="grey w-50 text-left"> {{$scheduleVisit->payment->transaction_id}}</span>
																			</div>
																			<div class="form-group td-tag d-flex justify-content-between align-items-start">
																				<span>Amount Paid</span>
																				<span class="turnicate1 grey w-50 text-left">
																					{{numberformatWithCurrency($scheduleVisit->payment->amount)}} ( for {{$scheduleVisit->TotalProperty}} Properties includes )
																				</span>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="col">
																			<div class="form-group td-tag d-flex justify-content-between align-items-start">
																				<span> Payment Mode</span>
																				<span class="turnicate1 grey w-50 text-left"> {{strtoupper($scheduleVisit->payment->method)}} </span>
																			</div>

																			<div class="form-group td-tag d-flex justify-content-between align-items-start">
																				<span> Payment Date </span>
																				<span class="grey w-50 text-left">
																					{{get_date_week_month_name($scheduleVisit->payment->created_at)}}</span>
																			</div>
																		</div>
																	</div>
																</div>
																<!-- mobile view end-->

																<div class="form-row d-none d-lg-flex">
																	<div class="col-md-6 right-border ntr">
																		<div class="col">
																			<div class="form-group td-tag">
																				Transaction ID
																			</div>
																			<div class="form-group td-tag">
																				Amount Paid
																			</div>
																		</div>
																		<div class="col">
																			<div class="form-group bio-tag">
																				{{$scheduleVisit->payment->transaction_id}}
																			</div>
																			<div class="form-group bio-tag">
																				{{numberformatWithCurrency($scheduleVisit->payment->amount)}} ( for {{$scheduleVisit->TotalProperty}} Properties includes )
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 ntr">
																		<div class="col">
																			<div class="form-group td-tag">
																				Payment Mode
																			</div>
																			<div class="form-group td-tag">
																				Payment Date
																			</div>
																		</div>
																		<div class="col">
																			<div class="form-group">
																				{{strtoupper($scheduleVisit->payment->method)}}
																			</div>
																			<div class="form-group">
																				{{get_date_week_month_name($scheduleVisit->payment->created_at)}}
																			</div>
																		</div>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="msgBox alert alert-success mt-4">
										<div class="d-flex">
											<svg class="mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
												<path fill="none" d="M0 0h24v24H0z" />
												<path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z" fill="rgba(83,214,135,1)" />
											</svg>
											<span class="font14 grey">Please get in touch with us on <a href="tel:{!! $configVariables['admincontact']['value'] !!}">{!! $configVariables['admincontact']['value'] !!}</a> in case of any query/issue.
											</span>
										</div>
									</div>
									<div class="mT50 text-center">
										<a href="{{ route('customer.dashboard.myvisit') }}" class="btn customBtn btn-success minw-184"> Go to My Visits </a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	@endsection
	@section('uniquePageScript')
	<script async src="https://static.addtoany.com/menu/page.js"></script>
	<script src="{{URL::to('theme/libs/fotorama/fotorama.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.btnprn').on('click', function() {
				window.print();
			});
		});
	</script>
	@endsection