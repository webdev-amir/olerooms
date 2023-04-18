@extends('layouts.app')
@section('title',"Booking Successfull".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content" id="bookingSuccesspage">
    <section class="bravo-list-tour padding50 bgDark m-0 bookingdetail_page">
        <div class="container">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active mr-3"> <span>Booking Details </span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active mr-3"> <span>Make Payment </span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false"> <span>Booking Confirmed </span></button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane show active" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="leftside_data bookingconfirm_tab">
                                <div class="booking_confirm">
                                    <figure class="mb-0 text-center">
                                        <img src="{{URL::to('images/bookingconfirm.svg')}}" alt="image not found" />
                                    </figure>
                                    <p class="font34 greendark medium mT30 text-center">Your Booking<br>
                                        <span class="green">#{{$bookingDetail->property->property_code}}</span> Successfully Done!
                                    </p>
                                </div>
                                <div class="printRow d-flex justify-content-between align-items-center mb-4">
                                    <span class="green font20 regular">property Info</span>
                                    <a href="javascript:;" class="printDetailsBooking"><img src="{{URL::to('images/printicon.svg')}}" alt="image not found" /></a>
                                </div>
                                <div class="accordion accordionCustom" id="accordionExample">
                                    <div class="card mb-3 border-bottom1">
                                        <div class="card-header p-0" id="bookingconfirm1_one">
                                            <h2 class="mb-0 lineHeight0">
                                                <button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#bookingconfirm1" aria-expanded="true" aria-controls="bookingconfirm1">
                                                    {{$bookingDetail->property->property_name}} ({{$bookingDetail->property->property_code}})
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="bookingconfirm1" class="collapse show" aria-labelledby="bookingconfirm1_one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-6">
                                                        <div class="mapSec">
                                                            <div class="w-100 native-content" id="embedMap" style="width: 400px; height: 300px;">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="lat" value="{{$bookingDetail->property->lat}}" />
                                                        <input type="hidden" id="long" value="{{$bookingDetail->property->long}}" />
                                                        <input type="hidden" id="map_location" value="{{$bookingDetail->property->map_location}}" />
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-6 pr-0">
                                                        <div>
                                                            <div class="visit-new-add d-flex">
                                                                <figure class="mr-3" style="background-image:url({{$bookingDetail->property->CoverImgThunbnail}}),url('{{onerrorReturnImage()}}');"></figure>
                                                                <div class="complete-detail pl-0">
                                                                    <div class="details-list-name">
                                                                        <h4 class="font20 medium black turnicate1">{{$bookingDetail->property->property_name}} ({{$bookingDetail->property->property_code}})</h4>

                                                                        <div class="rating_star detail-star-lists px-0">
                                                                            <div class="myratingview" data-rating="{{$bookingDetail->property->RatingAverage}}"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="checking d-block">
                                                                        <div class="date-in mb-2">
                                                                            <p class="grey font14">Total Booking Amount</p>

                                                                            <p class="book-confrom font16 black medium">{{numberformatWithCurrency($bookingDetail->total)??''}}
                                                                                @if(in_array($bookingDetail->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                                                ({{$bookingDetailJsonData->guests}} X Guests)
                                                                                @else
                                                                                ({{$bookingDetailJsonData->adult}} Adults - {{$bookingDetailJsonData->children}} Children)

                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="click-tag mt-2">

                                                                    @php
                                                                    $availableForFilter = config('custom.property_available_for');
                                                                    @endphp
                                                                    @if(!empty($bookingDetail->property->propertyAvailableFor))
                                                                    <p><a><img src="{{URL::to('images/hotel-icon.svg')}}" class="details-img">@foreach($bookingDetail->property->propertyAvailableFor as $propertyAvailableFor)
                                                                            {{$availableForFilter[$propertyAvailableFor->available_for]}}
                                                                            @if($loop->iteration != $loop->last),@endif
                                                                            @endforeach</a></p>
                                                                    @endif
                                                                    @if($bookingDetail->property->FurnishedTypeValue)
                                                                    <p>
                                                                        <a><img src="{{URL::to('images/sleep.svg')}}" class="details-img">{{ucfirst($bookingDetail->property->FurnishedTypeValue)}}</a>
                                                                    </p>
                                                                    @endif
                                                                    <p>
                                                                        <a><img src="{{URL::to('images/seat.svg')}}" class="details-img">{{$bookingDetail->property->total_seats}} Seats</a>
                                                                    </p>
                                                                </div>
                                                                <p class="mb-2 grey"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid"> {{$bookingDetail->property->map_location}}</p>
                                                                <div class="checking d-block">
                                                                    <div class="date-in mb-2">
                                                                        <p class="grey font14">Booking Date</p>
                                                                        <p class="book-confrom font16 black medium">{{get_date_week_month_name($bookingDetail->created_at) ?? ''}}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="checking d-block">
                                                                    <div class="date-in mb-2">
                                                                        <p class="grey font14">Check In Date</p>
                                                                        <p class="book-confrom font16 black medium">{{get_date_week_month_name($bookingDetail->check_in_date) ?? ''}}</p>
                                                                    </div>
                                                                    @if($bookingDetail->check_out_date)
                                                                    <div class="date-in mb-2">
                                                                        <p class="grey font14">Check Out Date</p>
                                                                        <p class="book-confrom font16 black medium">{{get_date_week_month_name($bookingDetail->check_out_date) ?? ''}}</p>
                                                                    </div>
                                                                    @endif
                                                                    @if(in_array($bookingDetail->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','guest-hotel']))
                                                                    <div class="date-in mb-2">
                                                                        <p class="grey font14">Room Type</p>
                                                                        <p class="book-confrom font16 black medium">{{ucfirst($bookingDetailJsonData->room_occupancy_type)}} ({{$bookingDetailJsonData->property_room_type}})</p>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card border-bottom1 mb-3">
                                        <div class="card-header p-0" id="bookingconfirm1_five">
                                            <h2 class="mb-0 lineHeight0">
                                                <button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#bookingconfirm5" aria-expanded="true" aria-controls="bookingconfirm5">
                                                    Transaction Details
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="bookingconfirm5" class="collapse show" aria-labelledby="bookingconfirm1_five">
                                            <div class="open-tag border-0 bgDark">
                                                <div class="collapse show" id="collapseExample">
                                                    <div class="card card-body">
                                                        <div class="">
                                                            <form>
                                                                @if($bookingDetail->payment)
                                                                <!-- mobile view -->
                                                                <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                                                    <div class="col-md-6 mobileInline_6">
                                                                        <div class="col">

                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span>Total Amount</span>
                                                                                <span class="grey w-50 text-left"> {{numberformatWithCurrency($bookingDetail->amount) ?? ''}}</span>
                                                                            </div>


                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span>Amount Paid</span>
                                                                                <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($bookingDetail->payment->amount) ?? ''}} </span>
                                                                            </div>

                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span>Discount Amount</span>
                                                                                <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($bookingDetail->final_offer_amount) ?? ''}} </span>
                                                                            </div>




                                                                            @if($bookingDetail->remaining_payable_amount > 0)
                                                                            <div class="form-group td-tag d-flex justify-content-between text-warning align-items-start">
                                                                                <span>Remaining Amount</span>
                                                                                <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($bookingDetail->remaining_payable_amount)??''}} </span>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="col">

                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span>Transaction ID</span>
                                                                                <span class="grey w-50 text-left"> {{$bookingDetail->payment->transaction_id ?? ''}}</span>
                                                                            </div>
                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span> Payment Mode</span>
                                                                                <span class="turnicate1 grey w-50 text-left"> {{strtoupper($bookingDetail->payment->method) ??''}} </span>
                                                                            </div>

                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span> Payment Date </span>
                                                                                <span class="grey w-50 text-left"> {{get_date_week_month_name($bookingDetail->payment->created_at) ?? ''}} </span>
                                                                            </div>

                                                                            <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                                <span> Discount Code Used</span>
                                                                                <span class="turnicate1 grey w-50 text-left">
                                                                                    @if($bookingDetail->agent_corp_code!='')
                                                                                    {{$bookingDetail->agent_corp_code}} - ({{$bookingDetail->code_type}})
                                                                                    @elseif($bookingDetailJsonData->offer_code!='')
                                                                                    {{$bookingDetailJsonData->offer_code}}
                                                                                    @else
                                                                                    N/A
                                                                                    @endif
                                                                                </span>
                                                                            </div>


                                                                        </div>



                                                                    </div>
                                                                </div>
                                                                <!-- mobile view end-->
                                                                <div class="form-row d-none d-lg-flex">
                                                                    <div class="col-md-6 right-border ntr">
                                                                        <div class="col">
                                                                            <div class="form-group td-tag">
                                                                                Total Amount
                                                                            </div>
                                                                            <div class="form-group td-tag">
                                                                                Amount Paid
                                                                            </div>
                                                                            <div class="form-group td-tag">
                                                                                Discount Amount
                                                                            </div>

                                                                            @if($bookingDetail->booking_payment_type =='partial')
                                                                            <div class="form-group td-tag">
                                                                                Remaining Amount
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="form-group bio-tag">
                                                                                {{numberformatWithCurrency($bookingDetail->amount)??''}}
                                                                            </div>
                                                                            <div class="form-group bio-tag">
                                                                                {{numberformatWithCurrency($bookingDetail->payment->amount)??''}}
                                                                            </div>
                                                                            <div class="form-group bio-tag ">
                                                                                {{numberformatWithCurrency($bookingDetail->final_offer_amount)??0}}
                                                                            </div>

                                                                            @if($bookingDetail->booking_payment_type =='partial')
                                                                            <div class="form-group bio-tag red">
                                                                                {{numberformatWithCurrency($bookingDetail->remaining_payable_amount)??0}}
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 ntr">
                                                                        <div class="col">
                                                                            <div class="form-group td-tag">
                                                                                Transaction ID
                                                                            </div>
                                                                            <div class="form-group td-tag">
                                                                                Payment Mode
                                                                            </div>
                                                                            <div class="form-group td-tag">
                                                                                Payment Date
                                                                            </div>
                                                                            <div class="form-group td-tag">
                                                                                Discount Code Used
                                                                            </div>
                                                                        </div>
                                                                        <div class="col">
                                                                            <div class="form-group bio-tag">
                                                                                {{$bookingDetail->payment->transaction_id ?? ''}}
                                                                            </div>
                                                                            <div class="form-group">
                                                                                {{strtoupper($bookingDetail->payment->method) ??''}}
                                                                            </div>
                                                                            <div class="form-group">
                                                                                {{get_date_week_month_name($bookingDetail->payment->created_at) ?? ''}}
                                                                            </div>
                                                                            <div class="form-group">
                                                                                @if($bookingDetail->agent_corp_code!='')
                                                                                {{$bookingDetail->agent_corp_code}} - ({{ucfirst($bookingDetail->code_type)}} Code)
                                                                                @elseif($bookingDetailJsonData->offer_code!='')
                                                                                {{$bookingDetailJsonData->offer_code}}
                                                                                @else
                                                                                N/A
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @else
                                                                Payment Not Complete
                                                                @endif
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3 border-bottom1">
                                        <div class="card-header p-0" id="bookingconfirm1_four">
                                            <h2 class="mb-0 lineHeight0">
                                                <button class="btn-link border-0 bg-transparent d-flex align-items-center	" type="button" data-toggle="collapse" data-target="#bookingconfirm4" aria-expanded="true" aria-controls="bookingconfirm4">
                                                    Customer Detail
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="bookingconfirm4" class="collapse show" aria-labelledby="bookingconfirm1_four">
                                            <div class="open-tag border-0 bgDark">
                                                <div class="borderRound p-3">
                                                    <form>
                                                        <!--mobile view-->
                                                        <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                                            <div class="col-12 mb-3">
                                                                <img src="{{$bookingDetail->user->PicturePath}}" alt="proimg" onerror="this.src='{{$bookingDetail->user->ErrorPicturePath}}'" width="100px" height="100px" />
                                                            </div>
                                                            <div class="col-md-6 mobileInline_6">
                                                                <div class="col">
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span>Name</span>
                                                                        <span class="turnicate1 grey w-50 text-left"> {{$bookingDetail->name}}</span>
                                                                    </div>
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span>Email</span>
                                                                        <span class="grey w-50 text-left"> {{$bookingDetail->email}} </span>
                                                                    </div>
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span>Phone</span>
                                                                        <span class="turnicate1 grey w-50 text-left"> {{$bookingDetail->phone}} </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="col">
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span> Check In Date</span>
                                                                        <span class="grey w-50 text-left"> {{get_date_week_month_name($bookingDetail->check_in_date) ?? ''}} </span>
                                                                    </div>
                                                                    @if(in_array($bookingDetail->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span> {{Str::plural('Guest',$bookingDetailJsonData->guests)}} </span>
                                                                        <span class="turnicate1 grey w-50 text-left"> {{$bookingDetailJsonData->guests}}
                                                                        </span>
                                                                    </div>
                                                                    @else
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span> {{Str::plural('Adult',$bookingDetailJsonData->adult)}} </span>
                                                                        <span class="turnicate1 grey w-50 text-left">{{$bookingDetailJsonData->adult}} </span>
                                                                    </div>
                                                                    <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                                        <span> {{Str::plural('Child',$bookingDetailJsonData->children)}}</span>
                                                                        <span class="turnicate1 grey w-50 text-left"> {{$bookingDetailJsonData->children}} </span>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--mobile view end-->

                                                        <div class="form-row d-none d-lg-flex">
                                                            <div class="col-md-6 right-border ntr">
                                                                <div class="col">
                                                                    <img src="{{$bookingDetail->user->PicturePath}}" alt="proimg" onerror="this.src='{{$bookingDetail->user->ErrorPicturePath}}'" width="100px" height="100px" />
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group td-tag">
                                                                        Name
                                                                    </div>
                                                                    <div class="form-group td-tag">
                                                                        Email
                                                                    </div>
                                                                    <div class="form-group td-tag">
                                                                        Phone
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetail->name}}
                                                                    </div>
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetail->email}}
                                                                    </div>
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetail->phone}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 ntr">
                                                                <div class="col">
                                                                    <div class="form-group td-tag">
                                                                        Check In Date
                                                                    </div>
                                                                    @if(in_array($bookingDetail->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                                    <div class="form-group td-tag">
                                                                        Guests
                                                                    </div>
                                                                    @else
                                                                    <div class="form-group td-tag">
                                                                        Adults
                                                                    </div>
                                                                    <div class="form-group td-tag">
                                                                        Children
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group bio-tag">
                                                                        {{get_date_week_month_name($bookingDetail->check_in_date) ?? ''}}
                                                                    </div>
                                                                    @if(in_array($bookingDetail->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetailJsonData->guests}}
                                                                    </div>
                                                                    @else
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetailJsonData->adult}}
                                                                    </div>
                                                                    <div class="form-group bio-tag">
                                                                        {{$bookingDetailJsonData->children}}
                                                                    </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="msgBox alert alert-success mt-4">
                                        <div class="d-flex">
                                            <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                                <path fill="none" d="M0 0h24v24H0z" />
                                                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z" fill="rgba(83,214,135,1)" />
                                            </svg>
                                            <span class="font14 grey">Please get in touch with us on <a href="tel:{!! $configVariables['admincontact']['value'] !!}">{!! $configVariables['admincontact']['value'] !!}</a> in case of any query/issue.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mT50 text-center">
                                        <a href="{{ route('customer.dashboard.mybooking') }}" class="btn customBtn btn-success minw-184"> Go to My Bookings </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('uniquePageScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
@endsection