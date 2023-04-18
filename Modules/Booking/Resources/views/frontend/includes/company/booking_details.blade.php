{!! Form::open(['method' => 'post','route' => ['company.booking.add'],'id'=>'F_AddBookingDetails']) !!}
{{ Form::hidden('user_id',Auth::user()->id, []) }}
{{ Form::hidden('property_id',$property->id, ['class'=>'property_id' ,'id'=>'propertyId']) }}
{{ Form::hidden('property_type_id',$property->property_type_id, ['class'=>'property_type_id' ,'id'=>'propertyTypeId']) }}
{{ Form::hidden('property_type_slug',$property->propertyType->slug, ['class'=>'property_type_slug' ,'id'=>'propertyTypeSlug']) }}

@if(request()->occupancy_type ||count(explode(',',request()->occupancy_type))==1)
@php $property_room_type =request()->occupancy_type; @endphp
@else
@php $property_room_type =''; @endphp
@endif
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-8">
        <div class="leftside_data">
            <div class="accordion accordionCustom">
                <div class="card border-0 mb-3">
                    <div class="card-header p-0" id="headingProDetails">
                        <h2 class="mb-0 lineHeight0">
                            <button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#collapseProDetails" aria-expanded="false" aria-controls="collapseProDetails">
                                Property Details
                            </button>
                        </h2>
                    </div>
                    <div id="collapseProDetails" class="collapse show" aria-labelledby="headingProDetails">
                        <div class="card-body">
                            <div class="visit-new-add d-flex">
                                <figure class="mr-4" style="background-image:url({{$property->CoverImgThunbnail}}),url('{{onerrorReturnImage()}}');"></figure>
                                <div class="complete-detail pl-0">
                                    <div class="details-list-name">
                                        <div class="detail-content mb-2 d-flex justify-content-between">
                                            @if($property->author->ComponyLogo!='' && config('custom.is_company_logo_show'))
                                            <div class="proLogo">
                                                <img src="{{$property->author->ComponyLogo}}" width="100px" height="40px" onerror="this.src='{{onerrorReturnImage()}}'" />
                                            </div>
                                            @endif
                                            <div class="rating_star detail-star-lists px-0">
                                                <div class="myratingview" data-rating="{{$property->RatingAverage}}"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="details-list-name">
                                        <h4>
                                            {{$property->property_code}}
                                        </h4>
                                    </div>

                                    <div class="click-tag mt-3">
                                        @php
                                        $availableForFilter = config('custom.property_available_for');
                                        @endphp
                                        @if(!empty($property->propertyAvailableFor))
                                        <p><a><img src="{{URL::to('images/hotel-icon.svg')}}" class="details-img">@foreach($property->propertyAvailableFor as $propertyAvailableFor)
                                                {{$availableForFilter[$propertyAvailableFor->available_for]}}
                                                @if($loop->iteration != $loop->last),@endif
                                                @endforeach</a></p>
                                        @endif
                                        @if($property->FurnishedTypeValue)
                                        <p><a><img src="{{URL::to('images/sleep.svg')}}" class="details-img">{{ucfirst($property->FurnishedTypeValue)}}</a></p>
                                        @endif
                                        <p><a><img src="{{URL::to('images/seat.svg')}}" class="details-img">{{$property->total_seats}} Seats</a></p>
                                    </div>
                                    <p class="mb-2 grey"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid"> {{$property->city->name.", ".$property->state->name}}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                @if($property->propertyType->slug=='flat')
                                <div class="form-group selecticon ermsg col-md-6">
                                    {{ Form::text('bhk',$property->bhk_type, ['required','class'=>'form-control flatbhk flatbhk_type','id'=>'flatBhkType','readonly']) }}
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 mb-3">
                    <div class="card-header p-0" id="headingGuestDetail">
                        <h2 class="mb-0 lineHeight0">
                            <button class="btn-link border-0 bg-transparent" type="button" data-toggle="collapse" data-target="#collapseGuestDetail" aria-expanded="false" aria-controls="collapseGuestDetail">
                                Guest Detail
                            </button>
                        </h2>
                    </div>
                    <div id="collapseGuestDetail" class="collapse show" aria-labelledby="headingGuestDetail">
                        <div class="card-body">
                            <h4 class="black font16 medium mb-3">Booking Detail</h4>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    @php
                                    $current_date = date('Y-m-d');
                                    $check_in_date = request()->get('check_in_date')?date('Y-m-d',strtotime(request()->get('check_in_date'))):$current_date;
                                    @endphp
                                    <div class="form-group">
                                        <label class="font16 grey"> Check In Date </label>
                                        <div class="form-content checkinDate">
                                            <div class="form-date-search smart-search">
                                                <div class="date-wrapper">
                                                    <div class="check-in-wrapper">
                                                        <div class="render check-in-render cursor-pointer">
                                                            {{date('d/m/Y',strtotime($check_in_date))}}
                                                            <i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="check-in-input" value="{{$check_in_date}}" name="check_in_date" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(in_array($property->propertyType->slug,['guest-hotel','hostel-pg-one-day','homestay']))
                                @php
                                $current_date = date('Y-m-d');
                                $check_out_date = request()->get('check_out_date')?date('Y-m-d',strtotime(request()->get('check_out_date'))):$current_date;
                                @endphp

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="font16 grey"> Check Out Date </label>
                                        <div class="form-content checkinDate">
                                            <div class="form-date-search smart-search">
                                                <div class="date-wrapper">
                                                    <div class="check-out-wrapper">
                                                        <div class="render check-out-render cursor-pointer">
                                                            {{date('d/m/Y',strtotime($check_out_date))}}
                                                            <i class="ri-calendar-line cal-icon calendar-check-in-out-fa"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" class="check-out-input" value="{{$check_out_date}}" name="check_out_date" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @php
                                $guests = request()->get('guests')?request()->get('guests'):1;
                                @endphp

                                @if(in_array($property->propertyType->slug,['flat','homestay']))
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group quantityset_row">
                                        <div class="val_quantity ">
                                            <a href="javascript:;" class="quantity__minus btn-minus-guests" data-input-class="quantity_guests">
                                                <i class="ri-subtract-line"></i>
                                            </a>
                                            <input name="total_guests" required type="text" min="1" max="{{$property->property_type_id==5 && $property->guest_capacity != '' ?$property->guest_capacity:10}}" class="quantity_guests quantity__input numberonly" value="{{$guests}}">
                                            <a href="javascript:;" class="quantity__plus btn-add-guests" data-input-class="quantity_guests">
                                                <i class="ri-add-line"></i>
                                            </a>
                                        </div>
                                        <label class="font16 grey"> Guest </label>
                                        <input type="text" value="" placeholder="Guest" class="form-control " readonly>
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                </div>
                                @else
                                {{ Form::hidden('total_guests',0, ['class'=>'total_guests','id'=>'totalGuests']) }}
                                @endif
                            </div>

                            @if(!in_array($property->propertyType->slug,['flat','homestay']))
                            <h4 class="black font16 medium mb-3 mt-2">Select Occupancy</h4>

                            @php
                            $guests = 0;
                            @endphp
                            @foreach($property->propertyRooms as $propertyRoom)
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group ermsg">
                                        <label class="font16 black">{{ucfirst($propertyRoom->room_type)}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group ermsg" @if($propertyRoom->is_ac == 0) style="display:none;" @endif>
                                        <label class="font16 black pr-2"> AC </label>
                                        <input type="checkbox" value="AC_{{$propertyRoom->id}}" @if($loop->iteration ==1) required @endif name="occupancy_room_type[]" class="room_ac_non_ac_checkbox ac_checkbox" data-id="{{$propertyRoom->id}}" data-type="ac" >
                                        <label class="font16 black pr-2">( Per room - {{numberformatWithCurrency($propertyRoom->ac_amount)}}) </label>
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>

                                    <div class="form-group ermsg" @if($propertyRoom->is_non_ac == 0) style="display:none;" @endif >
                                        <label class="font16 black pr-2"> NON/AC </label>
                                        <input type="checkbox" value="NON_AC_{{$propertyRoom->id}}" @if($loop->iteration == 1 &&$propertyRoom->is_ac == 0) required @endif name="occupancy_room_type[]" class="room_ac_non_ac_checkbox non_ac_checkbox" data-type="non_ac" data-id="{{$propertyRoom->id}}">
                                        <label class="font16 black pr-2">( Per room - {{numberformatWithCurrency($propertyRoom->non_ac_amount)}}) </label>
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group quantityset_row" @if($propertyRoom->is_ac == 0) style="display:none;" @endif>
                                        <div class="val_quantity val_quantity_company ">
                                            <a href="javascript:;" class="quantity__minus btn-minus-guests-multi btn-minus-{{$propertyRoom->id}}-ac" data-input-class="quantity_guests_{{$propertyRoom->id}}_ac">
                                                <i class="ri-subtract-line"></i>
                                            </a>
                                            <input type="hidden" name="guests_com[{{$propertyRoom->room_type}}][ac_price]" value="{{$propertyRoom->ac_amount}}">
                                            <input name="guests_com[{{$propertyRoom->room_type}}][ac]" type="text" min="0" max="{{$propertyRoom->ac_rented_seats??10}}" class="quantity_guests_{{$propertyRoom->id}}_ac quantity__input numberonly" value="0" data-peramount="{{$propertyRoom->ac_amount}}" data-total-amount="" readonly>
                                            <a href="javascript:;" class="quantity__plus btn-add-guests-multi btn-add-{{$propertyRoom->id}}-ac" data-input-class="quantity_guests_{{$propertyRoom->id}}_ac">
                                                <i class="ri-add-line"></i>
                                            </a>
                                        </div>
                                        <label class="font16 grey"> Guest </label>
                                        <input type="text" value="" placeholder="Guest" class="form-control " readonly>

                                    </div>


                                    <div class="form-group quantityset_row" @if($propertyRoom->is_non_ac == 0) style="display:none;" @endif >
                                        <div class="val_quantity val_quantity_company ">
                                            <a href="javascript:;" class="quantity__minus btn-minus-guests-multi btn-minus-{{$propertyRoom->id}}-non-ac" data-input-class="quantity_guests_{{$propertyRoom->id}}_non_ac">
                                                <i class="ri-subtract-line"></i>
                                            </a>
                                            
                                            <input type="hidden" name="guests_com[{{$propertyRoom->room_type}}][non_ac_price]" value="{{$propertyRoom->non_ac_amount}}">

                                            <input name="guests_com[{{$propertyRoom->room_type}}][non_ac]" type="text" min="0" max="{{$propertyRoom->non_ac_rented_seats??10}}" class="quantity_guests_{{$propertyRoom->id}}_non_ac quantity__input numberonly" data-peramount="{{$propertyRoom->non_ac_amount}}" data-total-amount="" value="0" readonly>
                                            <a href="javascript:;" class="quantity__plus btn-add-guests-multi btn-add-{{$propertyRoom->id}}-non-ac" data-input-class="quantity_guests_{{$propertyRoom->id}}_non_ac">
                                                <i class="ri-add-line"></i>
                                            </a>
                                        </div>
                                        <label class="font16 grey"> Guest </label>
                                        <input type="text" value="" placeholder="Guest" class="form-control " readonly>

                                    </div>
                                </div>

                            </div>

                            @endforeach

                            @endif
                            <h4 class="black font16 medium mb-3 mt-2">Personal Details</h4>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group ermsg">
                                        <label class="font16 grey">Name</label>
                                        <input type="text" placeholder="Name" name="name" class="form-control " required value="{{auth()->user()->name}}">
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group ermsg">
                                        <label class="font16 grey"> Email </label>
                                        <input type="email" value="{{auth()->user()->email}}" placeholder="Email" required name="email" class="form-control ">
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group ermsg">
                                        <label class="font16 grey"> Phone Number </label>
                                        <input type="text" value="{{auth()->user()->phone}}" required placeholder="Phone Number" name="phone" class="form-control numberonly">
                                        <span class="invalid-feedback error error-email"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--col-sm-8 close-->
    <div class="col-sm-12 col-md-12 col-lg-4">
        <div>
            <div class="rightsideInfo">
                <div class="sidewidget">
                    <h4 class="font20 medium black allover_padding mb-0"> Payment Summary </h4>
                </div>
                <div class="sidewidget border-0">
                    <div class="allover_padding">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Guest Details</div>
                            <div class="font18 grey medium totalGuests">0</div>
                        </div>

                        @if(in_array($property->propertyType->slug,['guest-hotel','hostel-pg-one-day','homestay']))
                        <!-- style="display:none !important;" -->
                        <div class="d-flex align-items-center justify-content-between mb-3 daysDiffernceDiv">
                            <div class="black regular font18">Total Days</div>
                            <div class="font18 grey medium daysDiffernce">0</div>
                        </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Amount </div>
                            <div class="font18 grey medium totalAmount">₹ 0</div>
                        </div>


                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="black regular font18">Discount</div>
                            <div class="font18 grey medium">- <span class="discountAmount"> ₹ 0</span></div>
                        </div>

                        <div class="input-group mb-3 inputfocus">
                            {{ Form::text('agent_corp_code_view',null, ['class'=>'form-control','id'=>'agentCompanyCode1', 'placeholder'=>'Agent/Company Code']) }}
                            {{Form::hidden('agent_corp_code',null,['id'=>'agentCompanyCode'])}}
                            {{Form::hidden('code_type',null,['id'=>'agentCompanyCodeType'])}}
                            <div class="input-group-prepend">
                                <span class="input-group-text bgDark grey border-0  medium font18 cursor-pointer applyCodeButton" id="applyCodeButton" data-loader="Applying Agent/Company Code" data-url="{{route('company.booking.applyAgentCode')}}">
                                    Apply
                                </span>
                            </div>
                        </div>

                        @if(count($property->propertyOffers) > 0 || count($property->propertyType->propertyGlobalOffers) > 0 )
                        <input type="hidden" value="" id="AppliedCouponCode">
                        <div class="input-group mb-3 inputfocus">
                            <select class="form-control" placeholder="Offer Code" aria-label="Offer Code" name="offer_code" id="offerCode" aria-describedby="basic-addon1" data-property-id="{{$property->id}}" data-url="{{route('company.booking.applycode')}}">
                                <option value="">Select Offer</option>
                                @foreach($property->propertyType->propertyGlobalOffers as $glOffer)
                                <option value="{{$glOffer->coupon_code}}">{{$glOffer->coupon_code}}</option>
                                @endforeach
                                @foreach($property->propertyOffers as $offer)
                                <option value="{{$offer->coupon->coupon_code}}">{{$offer->coupon->coupon_code}}</option>
                                @endforeach
                            </select>
                            <div class="input-group-prepend">
                                <span class="input-group-text bgDark border-0 grey medium font18 cursor-pointer applyOfferButton" id="basic-addon1" data-loader="Applying Offer Code">Apply</span>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex align-items-center couponapplied green font16 mb-2 mt-2" id="discountSucccessText" style="display: none !important;">
                            <i class="ri-checkbox-circle-fill font20 green mr-2"></i>
                            <span class="discountSucccessText">
                                Upto <span id="discountSucccessAmount"></span> off applied
                            </span>
                            <span class="ml-4 red" id="removeCoupon" style="cursor: pointer;">
                                Remove
                            </span>

                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="black regular font18">Payable Amount</div>
                            <div class="font20 green greenshade1 semibold finalPayableAmount">₹ 0</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rightsideInfo payment-type md-4 mt-4" style="display:{{$property->propertyType->is_partial== 0 ? 'none':''}};">
                <div class="sidewidget">
                    <h4 class="font20 medium black allover_padding mb-0"> Payment Type </h4>
                </div>
                <div class="allover_padding">
                    <div class="d-flex align-items-center">
                        <input type="radio" name="booking_payment_type" class="paidType" id="flexRadioDefault1" value="partial" {{$property->propertyType->is_partial== 0 ? '':'checked'}}>
                        <label for="flexRadioDefault1" class="black regular font18 ml-2">
                            Secure seat at <span class="text-warning propertyCommissionAmountText">₹0</span> Now.
                        </label>
                    </div>
                    <div class="d-flex justify-content-center my-2 black regular font20">
                        <span>Or</span>

                    </div>
                    <div class="d-flex align-items-center">
                        <input type="radio" name="booking_payment_type" class="paidType" value="full" id="flexRadioDefault2" {{$property->propertyType->is_partial== 0 ? 'checked':''}}>
                        <label for="flexRadioDefault2" class="black regular font18 ml-2">
                            Full Payment of <span class="green finalPayableAmount"></span> Now.
                        </label>
                    </div>
                </div>
            </div>


            <div class="bookingBtn mb-4 mt-3 d-flex align-items-center justify-content-between">
                <button type="submit" class="btn customBtn btn-success w-100 mr-3 directSubmit" id="AddBookingDetails" data-loader="Please wait, Property booking is processing"> Make Payment &nbsp; <span class="finalPayableAmount"></span> <i class="ri-arrow-right-line"></i> </button>
            </div>
            @if($property->security_deposit_amount != null)
            <div class="mT30">
                <p class="grey font15">
                    <span class="orangeshade">Note : * </span>This security deposit amount needs to be paid at the time of check-In.
                </p>
                <div class="msgBox alert alert-success">
                    <p class="mb-0 font18 grey"> Deposit <span class="green">{{numberformatWithCurrency($property->security_deposit_amount)}} </span>security money at time of check-in.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{ Form::hidden('days_diff',0, ['class'=>'days_difference','id'=>'daysDiff']) }}
{{ Form::hidden('booking_user_type','company') }}
{{ Form::hidden('total_guests',0, ['class'=>'total_guests','id'=>'totalGuests']) }}
{{ Form::hidden('discount',0, ['class'=>'payable_amount','id'=>'discountAmount']) }}
{{ Form::hidden('discount_type',0, ['class'=>'discount_type','id'=>'discountType']) }}
{{ Form::hidden('discount_value',0, ['class'=>'discount_value','id'=>'discountValue']) }}
{{ Form::hidden('amount',$property->amount, ['required','class'=>'total_amount','id'=>'totalAmount']) }}
{{ Form::hidden('total',null, ['required','class'=>'payable_amount','id'=>'finalPayableAmount']) }}
{{ Form::hidden('commission_percent',$property->propertyType->commission, ['required','class'=>'commission_percent','id'=>'commPercent']) }}
{{ Form::hidden('final_amount_after_selection',null, ['required','class'=>'finalAmountAfterSelectionInput','id'=>'finalAmountAfterSelection']) }}
{{ Form::hidden('property_commission_amount',null, ['required','class'=>'property_commission_amount','id'=>'propertyCommissionAmount']) }}
{{ Form::hidden('is_global_offer_applied',0, ['required','class'=>'is_global_offer_applied','id'=>'isGlobalOfferApplied']) }}

{!! Form::close() !!}