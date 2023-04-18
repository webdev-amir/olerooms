@if(count($dealoftheday)>0)
<section class="dealofthe_day">
	<div class="container">
		<div class="bravo-list-hotel layout_carousel hotel-slides m-0">
			<div class="title">Deals Of The Day</div>
			<div class="list-item">
				<div class="owl-carousel owl-loaded owl-drag">
				   <div class="owl-stage-outer">
				      <div class="owl-stage" style="transform: translate3d(-1125px, 0px, 0px); transition: all 3s ease 0s; width: 2250px;">
					        @foreach($dealoftheday as $coupon)
						        <div class="owl-item active" style="width: 266.25px; margin-right: 15px;">
						            <div class="item-loop yellowItem deal-of-the-day-div" data-toggle="modal" data-target="#couponModal" data-coupon-name="{{$coupon->title}}" data-coupon-code="{{$coupon->coupon_code}}" data-coupon-desc="{{$coupon->description}}" data-coupon-imgpath="{{$coupon->PicturePath}}" data-offerhref="{{route('search')}}?coupon_code={{$coupon->coupon_code}}">
										<div class="flipper">
											<div class="front_content">
												<div class="featured">{{$coupon->propertyType->name}}</div>
												<div class="thumb-image">
													<a href="javascript:;"><img src="{{$coupon->PicturePath}}" alt="image" /></a>
												</div>
												<div class="item-title">
													<a href="javascript:;">{{$coupon->coupon_code}} </a>
												</div>
												<div class="description_text turnicate1">{{ $coupon->title }}</div>
											</div>
											<div class="back_content">
												<div class="item-title mb-1">
													<a href="javascript:;">{{ $coupon->coupon_code }}</a>
													<input type="hidden" id="offer_rate" value="{{ $coupon->offer_rate }}">
												</div>
												<div class="description_text p-0">{{ (strlen($coupon->description) > 150) ? (substr($coupon->description, 0, 150) . ' ...') : $coupon->description }}</div>
											</div>
										</div>
									</div>
						        </div>
					        @endforeach
				      </div>
				   </div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header" id="image_coupon"></div>
			<div class="modal-body">
				<h4 id="title_coupon"></h4>
				<p id="desc_coupon"></p>
				<a href="{{route('search')}}" id="coupon_offer_route">
					<button type="button" class="btn customBtn btn-success minw-184">View Offer Properties</button>
				</a>
			</div>
		</div>
	</div>
</div>
@endif