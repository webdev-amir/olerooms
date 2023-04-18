@extends('layouts.app')
@section('title',"Property Details".trans('menu.pipe')." " .app_name())
@section('head')
<link rel="stylesheet" type="text/css" href="{{URL::to('theme/libs/fotorama/fotorama.css')}}">
@endsection
@section('content')
<div class="page-template-content" id="property_details">
    <section class="bravo_detail_hotel detail_page sec_pd  padding50 m-0 pb-0 selected list-detail-fly">
        <div class="bravo_content pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-6 selected fadeInUp animated2 delay1">
                        <div class="g-gallery">
                            <div class="fotorama" data-width="100%" data-thumbwidth="110" data-thumbheight="110" data-thumbmargin="0" data-nav="thumbs" data-allowfullscreen="true" data-loop="true">
                                <a href="{{$property->CoverImg}}" data-thumb="{{$property->CoverImgThunbnail}}" data-alt="Gallery"></a>
                                @if(count($property->propertyTotalRoomImages)>0)
                                @foreach($property->propertyTotalRoomImages as $list)
                                <a href="{{$list->RoomImageReal}}" data-thumb="{{$list->RoomImageThunbnail}}" data-alt="{{ucfirst($list->room_type)}}"></a>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="dirt_detail">
                            <div class="badge badge_custom mb-2 share-mr">
                                <div class="share-logo">
                                    <span class="flat-off">{{$property->PropertyType->name}}</span>
                                    @if($property->author->userCompleteProfileVerifired && $property->author->ComponyLogo!='' && config('custom.is_company_logo_show'))
                                    <img src="{{$property->author->ComponyLogo}}" onerror="this.src='{{onerrorReturnImage()}}'">
                                    @endif
                                </div>
                                <div class="share-in">
                                    <div class="icon-l-list service-wishlist {{$property->isWishList()}}" data-id="{{$property->id}}" data-type="{{$property->type}}">
                                        <i class="ri-heart-3-fill grey"></i>
                                    </div>
                                    <div class="icon-l-list-track" data-id="{{$property->id}}" data-type="{{$property->type}}">
                                        <a href="#" data-a2a-url="{{route('manageProperty.show',[$property->slug])}}" class="mr-0">
                                            <i class="ri-share-fill a2a_dd ml-0"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-detail-title">{{ $property->property_code }}
                                <div class="dirt-label detail-star-lists">
                                    <div class="myratingview" data-rating="{{$property->RatingAverage}}"></div>
                                </div>
                            </div>
                            <div class="title-30 mb-3 mt-1 room-set">Room start at: <span class="room-set-span">{{numberformatWithCurrency($property->PropertStartingAmount)}}</span></div>
                            <div class="f-600 mb-4"><span class="grey-90 f-400 f-14"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid">{{$property->search_address}}</div>
                            <div class="icon-list-detail mb-4">
                                @if(isset($property->propertyAmenities))
                                <div class="col-6">
                                    <h5>Amenities</h5>
                                    <hr>
                                    <ul>
                                        @foreach($property->propertyAmenities as $amenity)
                                        @if($loop->iteration < 8) <li>
                                            <img src="{{$amenity->amenities->PicturePath}}" class="icon-list-none front_amenity" alt="{{$amenity->amenities->name}}" onerror="this.src='{{onerrorReturnImage()}}'">{{$amenity->amenities->name}}
                                            </li>
                                            @else
                                            <li class="moreAmenites" style="display: none;">
                                                <img src="{{$amenity->amenities->PicturePath}}" class="icon-list-none front_amenity" alt="{{$amenity->amenities->name}}" onerror="this.src='{{onerrorReturnImage()}}'">{{$amenity->amenities->name}}
                                            </li>
                                            @endif
                                            @endforeach

                                            @if($property->propertyAmenities->count() > 8)
                                            <li>
                                                <a href="javascript:;" id="myBtn">Show More</a>
                                            </li>

                                            @endif
                                    </ul>

                                </div>
                                @endif
                                <div class="col-6">
                                    <h5>Property Details</h5>
                                    <hr>
                                    <ul>
                                        @php
                                        $availableForFilter = config('custom.property_available_for');
                                        @endphp
                                        @if(!empty($property->propertyAvailableFor))
                                        <li>
                                            <i class="fa fa-users front_amenity"></i>
                                            Available For :
                                            @foreach($property->propertyAvailableFor as $propertyAvailableFor)
                                            {{$availableForFilter[$propertyAvailableFor->available_for]}}
                                            @if($loop->iteration != $loop->last),@endif
                                            @endforeach
                                        </li>
                                        @endif

                                        @if(isset($property->furnished_type))<li><i class="fa fa-object-group front_amenity"></i>Furnished Type : {{$property->FurnishedTypeValue}}</li>@endif
                                        @if(isset($property->total_seats))<li><i class="fa fa-object-group front_amenity"></i>Total Seats : {{ $property->total_seats }}</li>@endif


                                        @foreach($property->propertyRooms as $rooms)
                                        @if(isset($rooms->room_type))
                                        <li><i class="fa fa-users icon-list-none front_amenity" aria-hidden="true"></i>Occupancies : {{ ucfirst($rooms->room_type) }}</li>@endif
                                        @if(isset($rooms->is_ac) || isset($rooms->is_non_ac))
                                        <li><i class="fa fa-cutlery icon-list-none front_amenity" aria-hidden="true"></i>
                                            Food Availability : {{ $rooms->ac_is_food_included == 1 || $rooms->non_ac_is_food_included == 1 ? 'Yes' : 'No'}}</li>
                                        @endif

                                        @endforeach
                                        @if(isset($property->carpet_area))<li><i class="fa fa-users front_amenity"></i>Carpet Area : {{$property->CarpetAreaInSq}}</li>@endif

                                        @if(isset($property->kitchen_modular))<li><i class="fa fa-cutlery icon-list-none front_amenity"></i>Kitchen Modular: {{ucfirst($property->kitchen_modular)}}</li>@endif

                                        @if(isset($property->parking_space_avail))<li><i class="fa fa-object-group front_amenity"></i>Parking space available: {{ucfirst($property->parking_space_avail)}}</li>@endif
                                    </ul>
                                </div>
                            </div>
                            <div class="detail-list-btn d-inline-flex propertydetailbtnRow">
                                @if($property->author->is_profileVerifiredApproved())
                                <input type="hidden" class="customerRoute{{$property->slug}}" value="{{route('booking.details',[$property->slug]).'?'.http_build_query(request()->query->all())}}">
                                <input type="hidden" class="companyRoute{{$property->slug}}" value="{{route('company.booking.details',[$property->slug]).'?'.http_build_query(request()->query->all())}}">
                                <button type="button" data-toggle="modal" class="bookNowButton" data-target="#bookingOptions" data-id="{{$property->slug}}"> Book Now </button>
                                @endif
                                @if($property->PropertyType->name == 'Flat')
                                {!! Form::open(['route' => 'schedulevisit.store','id'=>'F_AddToSchedule','autocomplete'=> 'off']) !!}
                                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}">
                                <button type="submit" id="AddToSchedule" class="list-visit form-submit directSubmit schedule_btn" data-loader="Please wait, Property adding to schedule"> Schedule Visit </button>
                                {!! Form::close() !!}
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bravo-slide-list  m-0 pb-0">
        <div class="container">
            @if(isset($property->property_description))
            <div class="detail-list-content">
                <h4>Overview</h4>
                <div class="native-content">{!! $property->property_description !!}</div>
            </div>
            @endif

            <div class="detail-list-content">
                <h4 class="mb-3">Rating & Reviews</h4>
                @forelse($property->propertyReviews as $review)
                <div class="native-content">
                    <div class="col-12 review-new">
                        <div class="review-letter">
                            <div class="slider-profile col-6"><img src="{{$review->user->ThumbPicturePath}}" width="30px" height="30px" onerror="this.src='{{onerrorProImage()}}'">
                                <div class="col-6"><span>{{$review->user->name}}</span>
                                    <p>{{get_date_month_name($review->publish_date)}}</p>
                                </div>
                            </div>
                            <div class="dirt-label detail-star-lists">
                                <div class="myratingview" data-rating="{{$review->rate_number}}"></div>
                            </div>
                        </div>
                        <div class="native-content mb-3">{{$review->content}}
                        </div>
                        @if($review->reply_content)
                        <div class="native-content crynew">
                            <h6>{{$review->property->property_code}}</h6> {{$review->reply_content}}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="native-content">
                    <div class="col-12 review-new">
                        No reviews found.
                    </div>
                </div>
                @endforelse
            </div>

            @if(isset($property->map_location))
            <div class="detail-list-content">
                <h4>Location</h4>
                <div class="mapSec">
                    <div class="w-100 native-content" id="embedMap" style="width: 400px; height: 300px;">
                    </div>
                </div>
                <input type="hidden" id="lat" value="{{$property->lat}}" />
                <input type="hidden" id="long" value="{{$property->long}}" />
                <input type="hidden" id="map_location" value="{{$property->map_location}}" />
            </div>
            @endif

            @if($property->YoutubeEmbededUrl)
            <div class="detail-list-content yotube_section">
                <iframe style="width: 100%" height="315" src="{{$property->YoutubeEmbededUrl}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            @endif

            @if(count($similarProperty) > 0)
            <div class="detail-list-content">
                <h4>Similar Properties</h4>
                <div class="native-content">
                    <section class="feature_property selected">
                        <div class="container">
                            <div class="bravo-list-hotel layout_carousel hotel-slides m-0">
                                <div class="list-item">
                                    <div class="owl-carousel owl-loaded owl-drag">
                                        <div class="owl-stage-outer">
                                            <div class="owl-stage" style="transform: translate3d(-285px, 0px, 0px); transition: all 0.25s ease 0s; width: 1425px;">
                                                @foreach ($similarProperty as $imgKey => $item)
                                                <div class="owl-item" style="width: 255px; margin-right: 30px;">
                                                    <div class="item-loop">
                                                        <div class="featured">{{$item->PropertyType->name}}</div>
                                                        <div class="thumb-image h-auto">
                                                            <a href="{{route('manageProperty.show',$item->slug)}}" target="_blank">
                                                                <div id="carouselExampleControls{{$imgKey}}" class="carousel slide h-100" data-ride="carousel" data-interval="false">
                                                                    <div class="carousel-inner h-100">
                                                                        <div class="carousel-item active h-100">
                                                                            <img src="{{$item->CoverImgThunbnail}}" class="d-block w-100" alt="Cover Image" onerror="this.src='{{onerrorReturnImage()}}'">
                                                                        </div>
                                                                        @if(count($item->propertyTotalRoomImages)>0)
                                                                        @foreach($item->propertyTotalRoomImages as $list)
                                                                        <div class="carousel-item h-100">
                                                                            <img src="{{$list->RoomImageThunbnail}}" class="d-block w-100" alt="{{ucfirst($list->room_type)}}" onerror="this.src='{{onerrorReturnImage()}}'">
                                                                        </div>
                                                                        @endforeach
                                                                        @endif
                                                                    </div>
                                                                    <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls{{$imgKey}}" data-slide="prev">
                                                                        <i class="ri-arrow-left-s-line"></i>
                                                                    </button>
                                                                    <button class="carousel-control-next" type="button" data-target="#carouselExampleControls{{$imgKey}}" data-slide="next">
                                                                        <i class="ri-arrow-right-s-line"></i>
                                                                    </button>
                                                                </div>
                                                            </a>
                                                            <div class="service-wishlist {{$item->isWishList()}}" data-id="{{$item->id}}" data-type="Property">
                                                                <i class="ri-heart-3-line"></i>
                                                            </div>
                                                        </div>
                                                        <div class="item-title">
                                                            <a href="javascript:;"> {{ucfirst($item->property_code)}}</a>
                                                        </div>
                                                        <div class="location">
                                                            <div class="cityText"><i class="ri-map-pin-line"></i> {{ucfirst($item->city->name)}} </div>
                                                            <div><span class="price_item">{{numberformatWithCurrency($item->PropertStartingAmount)}}</span></div>
                                                        </div>
                                                        <div class="rating_star detail-star-lists px-0">
                                                            <div class="myratingview" data-rating="{{$item->RatingAverage}}"></div>
                                                        </div>
                                                        <div class="list-btn" style="padding: 10px;">

                                                            <input type="hidden" class="customerRoute{{$item->slug}}" value="{{route('booking.details',[$item->slug]).'?'.http_build_query(request()->query->all())}}">
                                                            <input type="hidden" class="companyRoute{{$item->slug}}" value="{{route('company.booking.details',[$item->slug]).'?'.http_build_query(request()->query->all())}}">
                                                            <button type="button" data-toggle="modal" class="bookNowButton" data-target="#bookingOptions"> Book Now </button>


                                                            <button class="myBookingButton" data-id="{{$property->slug}}" data-action="{{auth()->user()?'':route('storePropertySessionId')}}">
                                                                <a href="{{(http_build_query(request()->query->all())) ? route('booking.details',[$item->slug]).'?'.http_build_query(request()->query->all()):route('booking.details',[$item->slug])}}" class="text-white">
                                                                    Book Now
                                                                </a>
                                                            </button>

                                                            @if($item->propertyType->slug == 'flat')
                                                            <button class="list-visit store-visit mx-2 schedule_btn" data-url="{{ route('schedulevisit.store') }}" data-id="{{ $item->id }}">Schedule Visit</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="owl-dots"><button role="button" class="owl-dot"><span></span></button><button role="button" class="owl-dot active"><span></span></button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            @endif
        </div>
    </section>
    {{--
        @include('includes.counter-section')
        --}}
</div>
@endsection
@section('uniquePageScript')
<script async src="https://static.addtoany.com/menu/page.js"></script>
<script src="{{URL::to('theme/libs/fotorama/fotorama.js')}}"></script>
@endsection