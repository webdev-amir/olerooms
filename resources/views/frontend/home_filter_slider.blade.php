<div class="bravo-form-search-all carousel bravo-form-search-slider">
	<div class="heroBanner">
		<div class="supportDiv">
			<p>
				<a href="{{route('contactus.create')}}">
					<img src="{{URL::to('images/support.svg')}}" alt="24hr Support" title="24hr Support" /> 24hr Support
				</a>
			</p>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="bannerContent pt-3">
						<div class="sub-heading">India's Largest Hostel/PG Booking Platform</div>
						<div class="scrolling-words-container">
							<div class="scrolling-words-box">
								<ul>
									@foreach($propertyTypes as $protype)
									<li>{{ucfirst($protype->name)}}</li>
									@endforeach
								</ul>
							</div>
						</div>
						<div class="text-center">
							<a href="{{route('search')}}?property_type=4" class="btn btn-danger br10 mt-3 mb-3 perday199" target="_blank">Room Booking Start @ 199/- Per Day</a>
						</div>
					</div>
					<div class="g-form-control">
						<ul class="nav nav-tabs" role="tablist">
							<div class="sideleftAbstract"></div>
							<div class="siderightAbstract"></div>
							@foreach($propertyTypes as $protype)
							<li role="{{$protype->slug}}">
								<a href="#{{$protype->slug}}" class="@if($loop->first) active @endif" aria-controls="{{$protype->slug}}" role="tab" data-toggle="tab">
									{{ucfirst($protype->name)}}
								</a>
							</li>
							@endforeach
						</ul>
						<div class="tab-content">
							@foreach($propertyTypes as $protype)
							<div role="tabpanel" class="tab-pane @if($loop->first) active @endif" id="{{$protype->slug}}">
								<form action="{{route('search')}}" class="form bravo_form" method="get">
									<div class="g-field-search">
										<div class="row">
											<div class="col-md-6 border-right">
												@include('frontend.includes.searchKey')
												{{-- @include('frontend.includes.city') --}}
											</div>
											@if($protype->slug=='homestay')
											<div class="col-md-2 border-right">
												@include('frontend.includes.checkin_date')
											</div>

											@else
											<div class="col-md-2 border-right">
												@include('frontend.includes.checkin_date')
											</div>
											@endif

											@if($protype->slug=='hostel-pg')
											<div class="col-md-2 border-right">
												@include('frontend.includes.occupancy')
											</div>
											<div class="col-md-2 border-right border-right-0">
												@include('frontend.includes.guests')
											</div>

											@elseif($protype->slug=='hostel-pg-one-day')
											<div class="col-md-2 border-right">
												@include('frontend.includes.checkout_date')
											</div>
											<div class="col-md-2 border-right border-right-0">
												@include('frontend.includes.guests')
											</div>

											@elseif($protype->slug=='homestay')
											<div class="col-md-2 border-right">
												@include('frontend.includes.checkout_date')
											</div>
											<div class="col-md-2 border-right border-right-0">
												@include('frontend.includes.guests')
											</div>

											@elseif($protype->slug=='guest-hotel')
											<div class="col-md-2 border-right">
												@include('frontend.includes.checkout_date')
											</div>
											<div class="col-md-2 border-right border-right-0">
												@include('frontend.includes.hotel_adults')
											</div>

											@elseif($protype->slug=='flat')
											<div class="col-md-2 border-right">
												@include('frontend.includes.flatbhk')
											</div>
											<div class="col-md-2 border-right border-right-0">
												@include('frontend.includes.flat_adults')
											</div>
											@endif
										</div>
									</div>
									<div class="g-button-submit">
										<input type="hidden" value="{{$protype->id}}" name="property_type" />
										<input type="hidden" value="a-to-z" name="orderby" />
										<input type="hidden" value="grid" name="searchLayout" />
										<button class="btn btn-success btn-search" type="submit">
											<i class="ri-search-line mr-3"></i> Search
										</button>
									</div>
								</form>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>