<div class="row">
    @forelse($records as $row)
    <div class="col-lg-3 col-md-6" id="myWishlist{{$row->property->id}}">
        <div class="item-loop">
            <div class="thumb-image ">
                <div>
                    <p class="tag-line">{{$row->property->PropertyType->name}}</p>
                </div>
                <div class="service-wishlist loading" data-id="{{$row->property->id}}" data-type="{{$row->property->type}}">
                    <i class="ri-heart-3-fill {{$row->property->isWishList()!=''?'fill-hrt':''}}"></i>
                </div>
                <a target="_blank" href="{{route('manageProperty.show',[$row->property->slug])}}">
                    <img class="img-responsive" src="{{$row->property->CoverImgThunbnail}}" alt="{{$row->property->property_code}}" title="{{$row->property->property_code}}" onerror="this.src='{{onerrorReturnImage()}}'" />
                </a>
            </div>
            <div class="content_wrap">
                <div class="item-title pb-0 px-0">
                    <a target="_blank" href="{{route('manageProperty.show',[$row->property->slug])}}">
                        {{$row->property->property_code}}
                    </a>
                </div>
                <div class="info px-0">
                    <div class="g-price">
                        <div class="price">
                            <span class="text-price">
                                <img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" />
                                {{$row->property->City->name}}
                            </span>
                        </div>
                        <div class="price-section"> <span class="unit">
                                {{numberformatWithCurrency($row->property->PropertStartingAmount)}}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="bg-transparent dirt-label detail-star-lists px-0">
                    <div class="myratingview" data-rating="{{$row->property->RatingAverage}}"></div>
                </div>
                <div class="list-btn" style="padding: 10px  0;">
                    <div class="list-btn">
                        <div class="row wishlist-btns-for-book">
                            <div class="col-md-6 col-6">
                                <button class="myBookingButton" data-id="{{$row->property->slug}}" data-action="{{auth()->user()?'':route('storePropertySessionId')}}">
                                    <a href="{{auth()->user()->hasRole('company') ? route('company.booking.details',[$row->property->slug]).'?'.http_build_query(request()->query->all()) :route('booking.details',[$row->property->slug]).'?'.http_build_query(request()->query->all())}}" class="text-white">
                                        Book Now
                                    </a>
                                </button>
                            </div>
                            @if($row->property->propertyType->slug == 'flat')
                            <div class="col-md-6  col-6">
                                <button class="list-visit store-visit schedule_btn" data-url="{{auth()->user->hasRole('company')? route('company.schedulevisit.store'):route('schedulevisit.store') }}" data-id="{{ $row->property->id }}">Schedule Visit</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-lg-12 noRecord wishlistnoreocrd"> No Wishlist Found</div>
    @endforelse
</div>
<br>
<div class=" pull-right">
    {!! $records->appends(request()->query())->links('front_dash_pagination') !!}
</div>