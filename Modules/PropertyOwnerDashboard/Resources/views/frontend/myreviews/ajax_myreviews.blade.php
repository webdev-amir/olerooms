 @if(isset($records) && count($records) > 0)
 @foreach($records as $key => $list)
 <div class="myreview_box d-flex">
    <figure style="background-image: url('{{ url( $list->user->PicturePath ) }}'),url('{{onerrorProImage()}}');"></figure>
    <div class="contentWrap">
       <div class="toprow d-flex align-items-center">
          <p class="grey font18 medium mb-0 mr-2">{{$list->user->name}}</p>
          <div class="rating_star">
             @if($list->rate_number)
             <ul class="review-star">
                @for( $i = 0 ; $i < 5 ; $i++ ) @if($i < $list->rate_number)
                   <span class="ri-star-fill checked"></span>
                   @else
                   <span class="ri-star-fill"></span>
                   @endif
                   @endfor
             </ul>
             @endif
          </div>
       </div>
       <div class="staticList mb-2 mt-2">
          <ul>
             <li> Booking ID : #{{$list->booking->code}} </li>
             <li> {{ucfirst($list->property->property_name)}} </li>
             <li> {{get_date_month_name($list->publish_date)}} </li>
          </ul>
       </div>
       <p class="turnicate3 font16 regular grey">
          {!! $list->content !!}
       </p>
       <?php /*
               <div class="reviewbtn">
                  <a href="#" class="grey font16 mr-4"> <i class="ri-reply-line mr-2 grey"></i> Reply</a>
                  <a href="#" class="orangeshade font16" data-toggle="modal" data-target="#reporttoadmin"> <i class="ri-feedback-line mr-2 orangeshade"></i> Report to admin</a>
               </div>
               */ ?>
    </div>
 </div>
 @endforeach
 @else
 <div class="col-lg-12 noRecord wishlistnoreocrd"> No Review Found</div>
 @endif
 <div class="custom_pagination frontpaginate mT30 pull-right">
    {!! $records->links('front_dash_pagination') !!}
 </div>