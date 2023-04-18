@if(count($trustedCustomers)>0)	
<section class="customer_trust bravo-list-hotel m-0 bravo-client-feedback bgredshade padding50">
	<div class="container">
		<div class="title heading-title text-center mb-5 mt-0">
			Trusted More than  10000 Customers
			<p class="simplified-sub mt-2"></p>
		</div>
	</div>
	<div class="list-item owl-carousel p-0">
		@foreach($trustedCustomers as $trustedCustomerList)
			<div class="item">
				<div class="feedbackouter d-flex">
					   <figure style="background-image: url('{{ $trustedCustomerList->PicturePath }}'),url('{{onerrorProImage()}}');"></figure>
					   <div class="content_wrap">
						<div class="ratingRow">
							<div><div class="clienttitle">{{$trustedCustomerList->name}}</div>
							<div class="sub_title">{{$trustedCustomerList->designation}}</div></div>
							<div class="rating_star mt-2">
								{!! str_repeat('<span class="ri-star-fill checked"></span>', $trustedCustomerList->rating) !!}
								{!! str_repeat('<span class="ri-star-fill"></span>', 5 - $trustedCustomerList->rating) !!}									
							</div>
						</div>
						<div class="desc mt-3">
							{!! $trustedCustomerList->description !!}
						</div>
					   </div>
				</div>
			</div>
		@endforeach
	  </div>
</section>
@endif