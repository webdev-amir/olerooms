 @if(count($featured)>0)
 <section class="feature_property" style="background: url(images/feature.jpg);" id="BookNowButtonPage">
   <div class="container">
     <div class="bravo-list-hotel layout_carousel hotel-slides m-0">
       <div class="title">Featured Properties</div>
       <div class="list-item">
         <div class="owl-carousel owl-loaded owl-drag">
           <div class="owl-stage-outer">
             <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 2850px;">
               @foreach($featured as $imgKey => $feature)
               <div class="owl-item active" style="width: 255px; margin-right: 30px;">
                 <div class="item-loop">
                   <div class="featured">{{$feature->propertyType->name}}</div>
                   <div class="thumb-image">
                     <a href="{{route('manageProperty.show',$feature->slug)}}" target="_blank">
                       <div id="carouselExampleControls{{$imgKey}}" class="carousel slide h-100" data-ride="carousel" data-interval="false">
                         <div class="carousel-inner h-100">
                           <div class="carousel-item active h-100">
                             <img src="{{$feature->CoverImgThunbnail}}" class="d-block w-100" alt="Cover Image" onerror="this.src='{{onerrorReturnImage()}}'">
                           </div>
                           @if(count($feature->propertyTotalRoomImages)>0)
                           @foreach($feature->propertyTotalRoomImages as $list)
                           <div class="carousel-item h-100">
                             <img src="{{$list->RoomImageThunbnail}}" class="d-block w-100" alt="{{ucfirst($list->room_type)}}" onerror="this.src='{{onerrorReturnImage()}}'">
                           </div>
                           @endforeach
                           @endif
                         </div>
                         <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls{{$imgKey}}" data-slide="prev">
                           <i class="ri-arfeature-left-s-line"></i>
                         </button>
                         <button class="carousel-control-next" type="button" data-target="#carouselExampleControls{{$imgKey}}" data-slide="next">
                           <i class="ri-arfeature-right-s-line"></i>
                         </button>
                       </div>
                     </a>
                     <div class="service-wishlist {{$feature->isWishList()}}" data-id="{{$feature->id}}" data-type="Property">
                       <i class="ri-heart-3-line"></i>
                     </div>
                   </div>
                   <div class="item-title">
                     <a href="{{route('manageProperty.show',$feature->slug)}}" target="_blank">
                       {{ucfirst($feature->property_code)}}
                     </a>
                   </div>


                   <div class="location">
                     <div class="cityText"><i class="ri-map-pin-line"></i> {{ucfirst($feature->city->name)}} </div>
                     <div><span class="price_item">{{numberformatWithCurrency($feature->PropertStartingAmount)}}</span></div>
                   </div>
                   <div class="rating_star detail-star-lists px-0">
                     <div class="myratingview" data-rating="{{$feature->RatingAverage}}"></div>
                   </div>
                   <div class="list-btn @if($feature->propertyType->slug == 'flat') both-button @endif" style="padding: 10px;">

                     @if($feature->author->is_profileVerifiredApproved())
                     <input type="hidden" class="customerRoute{{$feature->slug}}" value="{{route('booking.details',[$feature->slug]).'?'.http_build_query(request()->query->all())}}">
                     <input type="hidden" class="companyRoute{{$feature->slug}}" value="{{route('company.booking.details',[$feature->slug]).'?'.http_build_query(request()->query->all())}}">
                     <button type="button" data-toggle="modal" class="bookNowButton" data-target="#bookingOptions" id="bookNowButton" data-id="{{$feature->slug}}" data-action="{{auth()->user()?'':route('storePropertySessionId')}}"> Book Now </button>
                     @if($feature->propertyType->slug == 'flat')
                     <input type="hidden" class="customerScheduleRoute" value="{{route('schedulevisit.store')}}">
                     <input type="hidden" class="companyScheduleRoute" value="{{route('company.schedulevisit.store')}}">
                     <button class="mx-2 schedule_btn" data-toggle="modal" data-id="{{ $feature->id }}" data-target="#scheduleOptions" id="scheduleNowButton"> Schedule Visit </button>
                     @endif
                     @endif

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
 @endif