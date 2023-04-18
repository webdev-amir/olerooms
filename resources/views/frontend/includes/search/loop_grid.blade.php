<div class="col-lg-4 col-md-6" id="BookNowButtonPage">
    <div class="item-loop">
        <div class="featured">{{$row->propertyType->name}}</div>
        <div class="thumb-image listing-new">
            <a href="{{route('manageProperty.show',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                <img class="img-responsive lazy" src="{{$row->CoverImgThunbnail}}" alt="{{$row->property_code}}" title="{{$row->property_code}}" onerror="this.src='{{onerrorReturnImage()}}'" />
            </a>
            <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
                <i class="fa fa-heart-o"></i>
            </div>
        </div>
        <div class="list-content-wrap">
            <div class="item-title">
                <a href="{{route('manageProperty.show',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                    {{$row->property_code}}
                </a>
            </div>
            <div class="service-review">
                <span class="text-price">
                    <img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" />{{$row->city->name}}</span>
                <span class="review"> {{numberformatWithCurrency($row->PropertStartingAmount)}}</span>
            </div>
            <div class="bg-transparent dirt-label detail-star-lists px-0">
                <div class="myratingview" data-rating="{{$row->RatingAverage}}"></div>
            </div>
            <div class="list-btn @if($row->propertyType->slug == 'flat') both-button @endif">
                @if($row->author->is_profileVerifiredApproved())
                <input type="hidden" class="customerRoute{{$row->slug}}" value="{{route('booking.details',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                <input type="hidden" class="companyRoute{{$row->slug}}" value="{{route('company.booking.details',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                <button type="button" data-toggle="modal" class="bookNowButton" data-target="#bookingOptions" id="bookNowButton" data-id="{{$row->slug}}" data-action="{{auth()->user()?'':route('storePropertySessionId')}}"> Book Now </button>
                @if($row->propertyType->slug == 'flat')
                <input type="hidden" class="customerScheduleRoute" value="{{route('schedulevisit.store')}}">
                <input type="hidden" class="companyScheduleRoute" value="{{route('company.schedulevisit.store')}}">
                <button class="list-visit" data-toggle="modal" data-id="{{ $row->id }}" data-target="#scheduleOptions" id="scheduleNowButton"> Schedule Visit </button>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>