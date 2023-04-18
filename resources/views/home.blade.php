@extends('layouts.app')
@section('content')
<div class="page-template-content" id="landingpage">
	@include('frontend.home_filter_slider')
	@include('frontend.citySection')
	@include('frontend.AddSlider')
	@include('frontend.deal_of_the_day_slider')
	@include('frontend.featured_property')
	@include('includes.home-counter-section')
	@include('frontend.simplified')
	@include('frontend.keyFeatures')
	@include('frontend.trustedCustomerSection')
	<section class="brandSlider">
		@include('frontend.brandSlider')
	</section>
</div>
@endsection