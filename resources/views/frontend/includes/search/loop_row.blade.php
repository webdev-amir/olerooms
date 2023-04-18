<div class="col-lg-12 col-md-12" id="BookNowButtonPage">
    <div class="row-grid-section">
        <div class="item-loop">
            <div class="featured">
                {{$row->PropertyType->name}}
            </div>
            <div class="thumb-image listing-new-row col-5">
                <a href="{{route('manageProperty.show',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                    <img class="img-responsive lazy" src="{{$row->CoverImgThunbnail}}" alt="{{$row->property_code}}" title="{{$row->property_code}}" onerror="this.src='{{onerrorReturnImage()}}'">
                </a>
                <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
                    <i class="fa fa-heart-o"></i>
                </div>
            </div>
            <div class="list-content-wrap col-7">
                <div class="item-title grid-page">
                    <a href="{{route('manageProperty.show',[$row->slug]).'?'.http_build_query(request()->query->all())}}">
                        {{$row->property_code}}
                    </a>
                    <div class="bg-transparent dirt-label detail-star-lists px-0">
                        <div class="myratingview" data-rating="{{$row->RatingAverage}}"></div>
                    </div>

                </div>
                <div class="grid-location text-price mb-2"> <img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid"><span>{{$row->search_address}}</span></div>
                <div class="service-review-grid mb-3">
                    <span class="text-price"> Room start at: <span class="review">&nbsp;{{numberformatWithCurrency($row->PropertStartingAmount)}}</span></span>
                </div>
                <div class="grid-click-tag">
                    <p>
                        <a>
                            <img src="{{URL::to('images/hotel-icon.svg')}}" class="details-img">
                            @php
                            $availableForFilter = config('custom.property_available_for');
                            @endphp
                            @if(!empty($row->propertyAvailableFor))
                            @foreach($row->propertyAvailableFor as $propertyAvailableFor)
                            {{$availableForFilter[$propertyAvailableFor->available_for]}}
                            @if($loop->iteration != $loop->last),@endif
                            @endforeach
                            @endif{{$row->AvailableForType}}
                        </a>
                    </p>
                    @if($row->FurnishedTypeValue)
                    <p>
                        <a><img src="{{URL::to('images/sleep.svg')}}" class="details-img">{{$row->FurnishedTypeValue}}</a>
                    </p>
                    @endif
                    <p>
                        <a>
                            <img src="{{URL::to('images/seat.svg')}}" class="details-img">{{$row->total_seats}} Seats
                        </a>
                    </p>
                </div>
                <div class="list-btn both-button">

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
</div>