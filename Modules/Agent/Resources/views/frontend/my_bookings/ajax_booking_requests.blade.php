<div class="bookingRequest_row mT50">
   @if(count($bookingRequests)>0)
     <h2 class="title-bar mb-0"> My Bookings </h2>
      @foreach($bookingRequests as $booking)
      <div class="bookingrequest_box mb-3">
         <div class="box_inner w-100">
            <figure class="mb-0" style="background-image: url({{ $booking->property->CoverImg }}),url('{{onerrorReturnImage()}}');"></figure>
            <div class="content d-flex justify-content-between w-100">
               <div class="contentLeft">
                  <div class="grey font14 regular">Booking ID #{{$booking->id }} @if($booking->property && $booking->property->propertyType) <a href="javascript:;" class="badge badge-secondary ml-2 mr-2"> {{$booking->property->propertyType->name}}</a> @endif </div>
                  <p class="black medium font20 mb-2">{{ucfirst($booking->user->name)}}</p>
                  <p class="grey regular font16 turnicate2 mb-2 wordbreak">{{$booking->property->map_location}}</p>
                  <div class="inOut_date d-flex align-items-center justify-content-between">
                     @if($booking->check_in_date)
                     <div>
                        <p class="mb-0 black font15 medium">Check-in Date :</p>
                        <span class="font18 regular grey">{{ date('l, d F,Y',strtotime($booking->check_in_date)) }}</span>
                     </div>
                     @endif
                     @if($booking->check_out_date)
                     <div>
                        <p class="mb-0 black font15 medium">Check-out Date :</p>
                        <span class="font18 regular grey">{{ date('l, d F,Y',strtotime($booking->check_out_date)) }}</span>
                     </div>
                     @endif
                  </div>
               </div>
               <div class="contentRight pr-3">
                  <span class="grey font15 regular">Total Booking Amount : </span>
                  <p class="mb-0 green font24 medium d-flex align-items-center justify-content-end">{{numberformatWithCurrency($booking->total)}}<i class=" ml-2 mt-1 ri-information-fill grey2 font20 lineHeight0"></i></p>
               </div>
            </div>
         </div>
      </div>
      @endforeach
   @endif
</div>