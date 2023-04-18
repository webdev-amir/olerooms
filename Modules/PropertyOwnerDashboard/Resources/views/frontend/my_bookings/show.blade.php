@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "Booking Details".trans('menu.pipe')." " .app_name())
@section('content')
@php
$booking_data = json_decode($data->property_booking_data, false);
if(isset($booking_data->per_room_amount)){
$amount = $booking_data->per_room_amount ;
}
if(isset($booking_data->guests)){
$totalAmt = $amount * $booking_data->guests ;
}
if(isset($booking_data->bhk)){
$bhk = substr($booking_data->bhk, 0, 1);
$totalAmt = $amount * $bhk ;
}
@endphp
<div class="bravo_user_profile" id="myBookingVendorPageShow">
   <div class="container-fluid">
      <div class="row row-eq-height">
         <div class="col-md-3 slide-menu hidden-print">
            @include('propertyownerdashboard::includes.sidebar_profile_menu')
         </div>
         <div class="col-md-9 top-menu hidden-print">
            <div class="user-form-settings">
               <div>
                  <div class="dash_header d-flex justify-content-between">
                     <div aria-label="breadcrumb" class="breadcrumb-page-bar">
                        <ul class="page-breadcrumb p-0">
                           <li class=""><a href="{{route('vendor.dashboard.mybookings')}}">My Bookings </a>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                 <path fill="none" d="M0 0h24v24H0z"></path>
                                 <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path>
                              </svg>
                           </li>
                           <li class=" active">Bookings Details </li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('propertyownerdashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9 details-booking-content">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1 ">
                  <h2 class="title-bar">
                     <span>
                        Booking Details
                     </span>
                  </h2>
                  <div class="user-profile-lists">
                     <div class="user-profile-lists">
                        <div class="inner_content w-100">
                           <div class="printRow d-flex justify-content-between align-items-center mb-4">
                              <span class="green font20 regular">Property info</span>
                              <a href="javascript;:" class="printDetailsBooking hidden-print"><img src="{{URL::to('images/printicon.svg')}}" alt="image not found" /></a>
                           </div>
                           <div class="accordion accordionCustom">
                              <div class="card mb-3 border-bottom1">
                                 <div class="card-header p-0" id="bookingconfirm1_one">
                                    <h2 class="mb-0 lineHeight0">
                                       <button class="btn-link border-0 bg-transparent d-flex align-items-center" type="button" data-toggle="collapse" data-target="#bookingconfirm1" aria-expanded="true" aria-controls="bookingconfirm1">
                                          Property Details
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
                                             <input type="hidden" id="lat" value="{{$data->property->lat}}" />
                                             <input type="hidden" id="long" value="{{$data->property->long}}" />
                                             <input type="hidden" id="map_location" value="{{$data->property->map_location}}" />
                                          </div>
                                          <div class="col-sm-12 col-md-12 col-lg-6 pr-0">
                                             <div>
                                                <div class="visit-new-add d-flex">
                                                   <figure class="mr-3" style="background-image: url('{{ url( $data->property->CoverImg ) }}'),url('{{onerrorReturnImage()}}');"></figure>
                                                   <div class="complete-detail pl-0">
                                                      <div class="id-potal mb-4">
                                                         <p>Booking ID <span class="details-code">#{{ $data->code }}</span></p>
                                                      </div>
                                                      <div class="details-list-name">
                                                         <h4 class="font20 medium black turnicate1">{{$data->property->property_name}} ({{$data->property->property_code}})</h4>

                                                         <div class="rating_star detail-star-lists px-0">
                                                            <div class="myratingview" data-rating="{{$data->property->RatingAverage}}"></div>
                                                         </div>
                                                      </div>
                                                      <div class="checking d-block">
                                                         <div class="date-in mb-2">
                                                            <p class="grey font14">Total Booking Amount</p>

                                                            <p class="book-confrom font16 black medium">{{numberformatWithCurrency($data->total)??''}}
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
                                                      @if(!empty($data->property->propertyAvailableFor))
                                                      <p><a><img src="{{URL::to('images/hotel-icon.svg')}}" class="details-img">@foreach($data->property->propertyAvailableFor as $propertyAvailableFor)
                                                            {{$availableForFilter[$propertyAvailableFor->available_for]}}
                                                            @if($loop->iteration != $loop->last),@endif
                                                            @endforeach</a></p>
                                                      @endif
                                                      @if($data->property->FurnishedTypeValue)
                                                      <p>
                                                         <a><img src="{{URL::to('images/sleep.svg')}}" class="details-img">{{$data->property->FurnishedTypeValue}}</a>
                                                      </p>
                                                      @endif
                                                      <p>
                                                         <a><img src="{{URL::to('images/seat.svg')}}" class="details-img">{{$data->property->total_seats}} Seats</a>
                                                      </p>
                                                   </div>
                                                   @if($data->property->flat_no)
                                                   <p class="mb-2 grey"><img class="mr-2" src="{{URL::to('images/hotel-icon.svg')}}" alt="grid">Flat No. - {{$data->property->flat_no}}</p>
                                                   @endif
                                                   <p class="mb-2 grey"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid"> {{$data->property->map_location}}</p>
                                                   <div class="checking d-block">
                                                      <div class="date-in mb-2">
                                                         <p class="grey font14">Booking Date</p>
                                                         <p class="book-confrom font16 black medium">{{get_date_week_month_name($data->created_at) ?? ''}}</p>
                                                      </div>
                                                   </div>

                                                   <div class="checking d-block">
                                                      <div class="date-in mb-2">
                                                         <p class="grey font14">Check In Date</p>
                                                         <p class="book-confrom font16 black medium">{{get_date_week_month_name($data->check_in_date) ?? ''}}</p>
                                                      </div>
                                                      @if($data->check_out_date)
                                                      <div class="date-in mb-2">
                                                         <p class="grey font14">Check Out Date</p>
                                                         <p class="book-confrom font16 black medium">{{get_date_week_month_name($data->check_out_date) ?? ''}}</p>
                                                      </div>
                                                      @endif
                                                      @if(in_array($data->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','guest-hotel']) && $data->booked_by !='company')
                                                      <div class="date-in mb-2">
                                                         <p class="grey font14">Room Type</p>
                                                         <p class="book-confrom font16 black medium">{{ucfirst($jsonData->room_occupancy_type)}} ({{$jsonData->property_room_type}})</p>
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
                                             <!-- mobile view -->
                                             <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                                <div class="col-12 mb-3">
                                                   <img src="{{$data->user->PicturePath}}" alt="proimg" onerror="this.src='{{$data->user->ErrorPicturePath}}'" width="50px" height="50px" />
                                                </div>
                                                <div class="col-md-6 mobileInline_6">
                                                   <div class="col">
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Name</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{$data->name}}</span>
                                                      </div>
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Email</span>
                                                         <span class="grey w-50 text-left"> {{$data->email}} <br>
                                                            <a href="mailto:{{$data->email}}">
                                                               <i class="fa fa-envelope"></i>
                                                            </a>
                                                         </span>
                                                      </div>
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Phone</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{$data->phone}} </span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="col">
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Check In Date</span>
                                                         <span class="grey w-50 text-left"> {{get_date_week_month_name($data->check_in_date) ?? ''}} </span>
                                                      </div>

                                                      @if($data->booked_by =='company')
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Guests </span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{$jsonData->total_guests??0}} </span>
                                                      </div>

                                                      @else
                                                      @if(in_array($data->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Guests </span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{$jsonData->guests}} </span>
                                                      </div>
                                                      @else
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Adults </span>
                                                         <span class="turnicate1 grey w-50 text-left">{{$jsonData->adult}} </span>
                                                      </div>
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Children </span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{$jsonData->children}} </span>
                                                      </div>
                                                      @endif
                                                      @endif

                                                   </div>
                                                </div>
                                             </div>
                                             <!-- mobile view end -->

                                             <div class="form-row d-none d-lg-flex">
                                                <div class="col-md-6 right-border ntr">
                                                   <div class="col">
                                                      <div class="form-group td-tag">
                                                         <img src="{{$data->user->PicturePath}}" class="profile-img" alt="proimg" onerror="this.src='{{$data->user->ErrorPicturePath}}'" width="50px" height="50px" />
                                                      </div>

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

                                                      </div>
                                                      <div class="form-group bio-tag">
                                                         {{$data->name}}
                                                      </div>

                                                      <div class="form-group bio-tag">
                                                         {{$data->email}}
                                                         <a href="mailto:{{$data->email}}">
                                                            <i class="fa fa-envelope"></i>
                                                         </a>
                                                      </div>
                                                      <div class="form-group bio-tag">
                                                         {{$data->phone}}<br>


                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-6 ntr">
                                                   <div class="col">
                                                      <div class="form-group td-tag">
                                                         Check In Date
                                                      </div>

                                                      @if($data->booked_by =='company')
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Guests </span>
                                                      </div>
                                                      @else
                                                      @if(in_array($data->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
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
                                                      @endif


                                                   </div>
                                                   <div class="col">
                                                      <div class="form-group bio-tag">
                                                         {{get_date_week_month_name($data->check_in_date) ?? ''}}
                                                      </div>

                                                      @if($data->booked_by =='company')
                                                      <div class="form-group bio-tag">
                                                         {{$jsonData->total_guests??0}}
                                                      </div>

                                                      @else
                                                      @if(in_array($data->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
                                                      <div class="form-group bio-tag">
                                                         {{$jsonData->guests}}
                                                      </div>
                                                      @else
                                                      <div class="form-group bio-tag">
                                                         {{$jsonData->adult}}
                                                      </div>
                                                      <div class="form-group bio-tag">
                                                         {{$jsonData->children}}
                                                      </div>
                                                      @endif
                                                      @endif

                                                   </div>
                                                </div>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              @if(in_array($data->property->propertyType->slug,['hostel-pg','hostel-pg-one-day','guest-hotel']) && $data->booked_by =='company')
                              <div class="card mb-3">
                                 <div class="card-header p-0" id="bookingconfirm1_six">
                                    <h2 class="mb-0 lineHeight0">
                                       <button class="btn-link border-0 bg-transparent d-flex align-items-center	" type="button" data-toggle="collapse" data-target="#bookingconfirm6" aria-expanded="true" aria-controls="bookingconfirm4">
                                          Booking Room Details
                                          <span class="green">
                                             (#{{$data->code}})
                                          </span>
                                       </button>
                                    </h2>
                                 </div>
                                 <div id="bookingconfirm6" class="collapse show" aria-labelledby="bookingconfirm1_six">

                                    <div class="user-profile-lists booking-selector">
                                       <div class="inner_content w-100">
                                          <div class="table-responsive  customtable_responsive br30" id="result">
                                             <table class="table tableDesign m-0">
                                                <thead>
                                                   <tr>
                                                      <th scope="col" class="text-center">Room Type</th>
                                                      <th scope="col">AC / NON-AC</th>
                                                      <th scope="col">Rooms/Guests</th>
                                                      <th scope="col">Per Room Amount</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @if(!empty($jsonData->guests_com))
                                                   @foreach($jsonData->guests_com as $key => $guests)

                                                   @if($guests->ac > 0)
                                                   <tr>
                                                      <td scope="row" class="text-center"> <span class="turncateText">{{ucfirst($key)}}</span> </td>
                                                      <td scope="row">
                                                         AC
                                                      </td>
                                                      <td scope="row">
                                                         {{$guests->ac }}
                                                      </td>
                                                      <td class="check-date">
                                                         {{numberformatWithCurrency($guests->ac_price ?? 0) }}
                                                      </td>
                                                   </tr>
                                                   @endif
                                                   @if($guests->non_ac > 0)
                                                   <tr>
                                                      <td scope="row" class="text-center"> <span class="turncateText">{{ucfirst($key)}}</span> </td>

                                                      <td scope="row">
                                                         NON-AC
                                                      </td>
                                                      <td scope="row">
                                                         {{$guests->non_ac}}
                                                      </td>
                                                      <td class="check-date">
                                                         {{ numberformatWithCurrency($guests->non_ac_price ?? 0)}}
                                                      </td>
                                                   </tr>
                                                   @endif
                                                   @endforeach
                                                   @endif


                                                </tbody>
                                             </table>

                                          </div>
                                       </div>
                                    </div>
                                 </div>

                              </div>
                              @endif
                              @if(isset($data->payment))
                              <div class="card border-bottom1 mb-3">
                                 <div class="card-header p-0" id="bookingconfirm1_five">
                                    <h2 class="mb-0 lineHeight0">
                                       <button class="btn-link border-0 bg-transparent d-flex align-items-center	" type="button" data-toggle="collapse" data-target="#bookingconfirm5" aria-expanded="true" aria-controls="bookingconfirm5">
                                          Transaction Details
                                       </button>
                                    </h2>
                                 </div>
                                 <div id="bookingconfirm5" class="collapse show" aria-labelledby="bookingconfirm1_five">
                                    <div class="open-tag border-0 bgDark">
                                       <div class="collapse show">
                                          <div class="card card-body">
                                             <!-- mobile view -->
                                             <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                                <div class="col-md-6 mobileInline_6">
                                                   <div class="col">

                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Total Amount</span>
                                                         <span class="grey w-50 text-left"> {{numberformatWithCurrency($data->amount) ?? ''}}</span>
                                                      </div>


                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Amount Paid</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($data->payment->amount) ?? ''}} </span>
                                                      </div>

                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Discount Amount</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($data->final_offer_amount) ?? ''}} </span>
                                                      </div>




                                                      @if($data->remaining_payable_amount > 0)
                                                      <div class="form-group td-tag d-flex justify-content-between text-warning align-items-start">
                                                         <span>Remaining Amount</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{numberformatWithCurrency($data->remaining_payable_amount)??''}} </span>
                                                      </div>
                                                      @endif
                                                   </div>
                                                </div>
                                                <div class="col-md-6">
                                                   <div class="col">

                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span>Transaction ID</span>
                                                         <span class="grey w-50 text-left"> {{$data->payment->transaction_id ?? ''}}</span>
                                                      </div>
                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Payment Mode</span>
                                                         <span class="turnicate1 grey w-50 text-left"> {{strtoupper($data->payment->method) ??''}} </span>
                                                      </div>

                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Payment Date </span>
                                                         <span class="grey w-50 text-left"> {{get_date_week_month_name($data->payment->created_at) ?? ''}} </span>
                                                      </div>

                                                      <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                         <span> Discount Code Used</span>
                                                         <span class="turnicate1 grey w-50 text-left">
                                                            @if($data->agent_corp_code!='')
                                                            {{$data->agent_corp_code}}
                                                            {{--
                                                         -({{ucfirst($data->code_type)}} Code)
                                                            --}}
                                                            @elseif($jsonData->offer_code!='')
                                                            {{$jsonData->offer_code}}
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

                                                      @if($data->booking_payment_type =='partial')
                                                      <div class="form-group td-tag">
                                                         Remaining Amount
                                                      </div>
                                                      @endif
                                                   </div>
                                                   <div class="col">
                                                      <div class="form-group bio-tag">
                                                         {{numberformatWithCurrency($data->amount)??''}}
                                                      </div>
                                                      <div class="form-group bio-tag">
                                                         {{numberformatWithCurrency($data->payment->amount)??''}}
                                                      </div>
                                                      <div class="form-group bio-tag ">
                                                         {{numberformatWithCurrency($data->final_offer_amount)??0}}
                                                      </div>

                                                      @if($data->booking_payment_type =='partial')
                                                      <div class="form-group bio-tag red">
                                                         {{numberformatWithCurrency($data->remaining_payable_amount)??0}}
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
                                                         {{$data->payment->transaction_id ?? ''}}
                                                      </div>
                                                      <div class="form-group">
                                                         {{strtoupper($data->payment->method) ??''}}
                                                      </div>
                                                      <div class="form-group">
                                                         {{get_date_week_month_name($data->payment->created_at) ?? ''}}
                                                      </div>
                                                      <div class="form-group">
                                                         @if($data->agent_corp_code!='')
                                                         {{$data->agent_corp_code}}
                                                         {{--
                                                         -({{ucfirst($data->code_type)}} Code)
                                                         --}}
                                                         @elseif($jsonData->offer_code!='')
                                                         {{$jsonData->offer_code}}
                                                         @else
                                                         N/A
                                                         @endif
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
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
   </div>
</div>
@endsection
@section('uniquePageScript')
<script type='text/javascript'>
   $('.print_booking_details').css('cursor', 'pointer');
   $(document).on('click', '.print_booking_details', function() {
      $('#collapseExample').show();
      $('#collapseExample1').show();
      window.print();
   });
</script>
@endsection