{!! Form::open(['route' => 'schedulevisit.update','id'=>'F_UpdateToSchedule','autocomplete'=> 'off']) !!}
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-8">
		<div class="leftside_data">

			<input type="hidden" name="schedule_visits_id" id="schedule_visits_id" value="{{$scheduleVisit->id}}">
			<div class="accordion accordionCustom">
				<div class="card border-0 mb-3">
					<div class="card-header p-0" id="headingOne">
						<h2 class="mb-0">
							<button class="btn-link border-0 bg-transparent" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								Personal Detail
							</button>
						</h2>
					</div>
					<div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="font16 grey">Full Name</label>
										<input type="text" placeholder="Full Name" name="name" class="form-control" value="{{ auth()->user()->name }}" readonly>
										<span class="invalid-feedback error error-name"></span>
									</div>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group">
										<label class="font16 grey"> Email ID </label>
										<input type="text" placeholder="Email ID" name="email" class="form-control " value="{{ auth()->user()->email }}" readonly>
										<span class="invalid-feedback error error-email"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@foreach($scheduleVisit->scheduleVisitProperty as $key => $val)
				<div class="card border-0 mb-3">
					<div class="card-header p-0" id="heading{{$key}}">
						<h2 class="mb-0">
							<button class="btn-link border-0 bg-transparent d-flex align-items-center	" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="false" aria-controls="collapse{{$key}}"><span class="create-span">{{$key+1}}</span>{{$val->property->property_name}} ({{ $val->property->property_code}})
							</button>
							<div class="visit-property loading visit-remove-icon" data-id="{{$val->id}}" data-url="{{ route('schedulevisit.delete')}}">
								<i class="fa fa-trash"></i>
							</div>
						</h2>
					</div>
					<div id="collapse{{$key}}" class="collapse show" aria-labelledby="heading{{$key}}">
						<div class="card-body">
							<div class="visit-new-add d-flex">
								<figure class="mr-4" style="background-image:url({{$val->property->CoverImgThunbnail}}),url('{{onerrorReturnImage()}}');"></figure>
								<div class="complete-detail pl-0">
									<div class="details-list-name">
										<div class="detail-content mb-2 d-flex justify-content-between">
											<div class="proLogo">
												@if($val->property->author->userCompleteProfileVerifired && $val->property->author->ComponyLogo !='' && config('custom.is_company_logo_show'))
												<img src="{{@$val->property->author->ComponyLogo}}" alt="image not found" class="com-logo-img" onerror="this.src='{{onerrorReturnImage()}}'">
												@endif
											</div>
											<div class="rating_star detail-star-lists px-0">
												<div class="myratingview" data-rating="{{$val->property->RatingAverage}}"></div>
											</div>
										</div>
									</div>
									<div class="click-tag mt-3">
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

										@if($val->property->FurnishedTypeValue)
										<p><a><img src="{{URL::to('images/sleep.svg') }}" class="details-img">{{$val->property->FurnishedTypeValue}}</a></p>
										@endif
										@if($val->property->total_seats)
										<p><a><img src="{{URL::to('images/seat.svg') }}" class="details-img">{{$val->property->total_seats}}</a></p>
										@endif
									</div>
									<p class="mb-2 grey"><img class="mr-2" src="{{URL::to('images/map-icon.svg') }}" alt="grid"> {{ $val->property->city->name}}, {{ $val->property->state->name}}</p>
									<div class="owner-confilg d-flex mt-3">
										<div><i class="ri-information-fill font35 mr-2 green"></i></div>
										<div>
											<p class="black"> Convinient time to visit property:</p>
											<p class="grey">
												@if($val->property->convenient_time)
												Owner will be available on {{$val->property->convenient_time}}
												@else
												N/A
												@endif
											</p>
										</div>
									</div>
								</div>
							</div>
							<div class="row mt-3">
								<div class="col-sm-12">
									<h4 class="mb-2 black font20 medium">Visiting Details</h4>
								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group selecticon mb-0 ermsg">
										<label class="font16 grey">Date</label>
										@php
										$current_date = date('Y-m-d');
										$visitDate = (!empty($val->visit_date) && $val->visit_date != '0000-00-00')?$val->visit_date: $current_date;
										@endphp
										<div class="form-visit-date">
											<div class="visit-date-wrapper">
												<div class="visit-in-wrapper">
													<div class="render visit-date-render cursor-pointer">
														<i class="ri-calendar-line cal-icon mr-1 calendar-check-in-out-fa"></i>
														{{date('Y-m-d',strtotime($visitDate))}}
													</div>
												</div>
												<span class="invalid-feedback error error-email"></span>
											</div>
											<input type="hidden" class="visit-date-input form-control" value="{{$visitDate}}" name="visit[{{$val->id}}][date]" required />
										</div>
										<!-- <input type="text" value="{{(!empty($val->visit_date) && $val->visit_date != '0000-00-00')?$val->visit_date:''}}" placeholder="dd/mm/yyyy" name="visit[{{$val->id}}][date]" class="form-control visit_dates" required> -->
									</div>

								</div>
								<div class="col-sm-12 col-md-6">
									<div class="form-group selecticon mb-0 ermsg">
										<!-- <i class="ri-calendar-2-line grey"></i> -->
										<label class="font16 grey"> Time </label>
										<input type="text" value="{{(!empty($val->visit_time) && $val->visit_time != '00:00:00')?$val->visit_time:''}}" placeholder="HH:MM" name="visit[{{$val->id}}][time]" class="form-control visit_time" required readonly>
										<span class="invalid-feedback error error-email"></span>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				@endforeach
				@if($scheduleVisit->TotalProperty < 3) <div class="addproperty d-flex align-items-center justify-content-center">
					<a href="{{ route('search') }}?property_type=2"><i class="ri-add-line"></i></a>
					<span class="font16 medium black d-block mt-3"> Add New Property </span>
					<span class="grey font15 regular mt-2">You can add upto 3 properties under the same amount.</span>
			</div>
			@endif
		</div>

	</div>
</div>
<div class="col-sm-12 col-md-12 col-lg-4">
	<div class="rightsideInfo">
		<div class="sidewidget">
			<h4 class="font20 medium black allover_padding mb-0"> Payment Summary </h4>
		</div>
		<div class="sidewidget border-0">
			<div class="allover_padding">
				<div class="d-flex align-items-center justify-content-between">
					<div class="black regular font18">Payable Amount</div>
					<div class="font20 green greenshade1 semibold">{{numberformatWithCurrency(setting_item('schedule_visit_amount'))}}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="bookingBtn mb-4 mt-3 d-flex align-items-center justify-content-between">
		<button type="submit" id="UpdateToSchedule" data-loader="Please wait, Property adding to schedule" class="btn btn-success btndefault customBtn w-100 mt-4 mb-4 form-submit directSubmit"> Make Payment of &nbsp; {{numberformatWithCurrency(setting_item('schedule_visit_amount'))}} <i class="ri-arrow-right-line"></i></button>
	</div>
</div>
</div>

<!-- <div class="row">
	<div class="col-sm-12 col-md-12 col-lg-8">
		<button type="submit" id="UpdateToSchedule" data-loader="Please wait, Property adding to schedule" class="btn btn-success btndefault customBtn w-100 mt-4 mb-4 form-submit directSubmit">Make Payment of {{numberformatWithCurrency(setting_item('schedule_visit_amount'))}}</button>
	</div>
</div> -->
{!! Form::close() !!}