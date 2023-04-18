@extends('mydashboard::layouts.dashboard_master')
@section('title', "My Visit Details".trans('menu.pipe')." " .app_name())
@section('section_type_dashboard',"wishlist-section")
@section('content')
<div class="bravo_user_profile" id="my_wishlist_page">
   <div class="container-fluid">
      <div class="row row-eq-height">
         <div class="col-md-3 slide-menu hidden-print">
            @include('mydashboard::includes.sidebar_profile_menu')
         </div>
         <div class="col-md-9 top-menu hidden-print">
            <div class="user-form-settings">
               <div>
                  <div class="dash_header d-flex justify-content-between">
                     <div aria-label="breadcrumb" class="breadcrumb-page-bar">
                        <ul class="page-breadcrumb p-0">
                           <li class="breadcrumb-item"><a href="{{route('customer.dashboard.myvisit')}}"> My Visits </a></li>
                           <li class="breadcrumb-item  active">My Visit Details</li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('mydashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9 details-booking-content">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1 ">
                  <h2 class="title-bar">
                     <span>
                        My Visiting Details
                     </span>
                  </h2>
                  <div class="user-profile-lists">
                     <div class="inner_content w-100">
                        <div class="details-table">
                           <div class="details-heading">
                              <h5 class="visits-id"> Booking @if($data->schedule_code)ID <span class="details-code">#{{$data->schedule_code}}</span>@endif</h5>
                              <div class="icon_wrap_new d-flex">
                                 <img style="cursor: pointer;" class="hidden-print mr-2" onclick="window.print()" src="{{URL::to('images/print.svg')}}">
                                 <div class="icon-l-list-track" data-id="{{$data->id}}" data-type="{{$data->type}}">
                                    <a href="#" data-a2a-url="{{route('customer.dashboard.myvisit.details',[$data->slug])}}" class="mr-0">
                                       <img style="cursor: pointer;" class="hidden-print a2a_dd" src="{{URL::to('images/share-new.svg')}}">
                                       <!-- <i class="ri-share-fill a2a_dd ml-0"></i> -->
                                    </a>
                                 </div>
                              </div>

                           </div>
                        </div>
                        @if(isset($data->scheduleVisitProperty))
                        @foreach($data->scheduleVisitProperty as $key => $propertyList)
                        <div class="accordin-first olddesign_accordien">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="open-tag">
                                    <a class="details-accordin vist-open" data-toggle="collapse" href="#collapseExample{{$key}}" role="button" aria-expanded="true" aria-controls="collapseExample{{$key}}">
                                       <span class="create-span">{{$key+1}}</span> {{ucfirst($propertyList->property->property_name)}} ({{$propertyList->property->property_code}})
                                    </a>
                                    <div class="collapse show" id="collapseExample{{$key}}">
                                       <div class="card card-body visit-new-add">
                                          <div class="">
                                             <form>
                                                <div class="form-row">
                                                   <div class=" col-3">
                                                      <img src="{{$propertyList->property->CoverImgThunbnail}}" class="detail-image" style="width: 100%" onerror="this.src='{{onerrorReturnImage()}}'">
                                                   </div>
                                                   <div class="visit-track col-9">
                                                      <div class="form-group ">
                                                         <div class="complete-detail">
                                                            <div class="id-potal mb-2"></div>
                                                            <div class="details-list-name">
                                                               <div class="detail-content mb-2">
                                                                  <h5>{{$propertyList->property->property_name}} ({{$propertyList->property->property_code}})</h5>
                                                                  <div class="star-rate">
                                                                     <div class="myratingview" data-rating="{{$propertyList->property->RatingAverage}}"></div>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <p class="mb-2"><img class="mr-2" src="{{URL::to('images/map-icon.svg')}}" alt="grid">{{$propertyList->property->full_address}}, {{$propertyList->property->city->name}}, {{$propertyList->property->state->name}}</p>
                                                            <div class="checking col-5">
                                                               <div class="date-in">
                                                                  <p>Visiting Date</p>
                                                                  <p class="book-confrom">{{ get_date_week_month_name($propertyList->visit_date) }}</p>
                                                               </div>
                                                               <div class="date-in">
                                                                  <p>Visiting Time</p>
                                                                  <p class="book-confrom">{{display_time($propertyList->visit_time)}}</p>
                                                               </div>
                                                            </div>
                                                            <div class="click-tag mt-3">
                                                               @foreach($propertyList->property->propertyAmenities as $amenity)
                                                               <p>
                                                                  <a>
                                                                     <img src="{{$amenity->amenities->PicturePath}}" class="details-img" onerror="this.src='{{onerrorReturnImage()}}'">{{$amenity->amenities->name}}
                                                                  </a>
                                                               </p>
                                                               @endforeach
                                                            </div>
                                                            <div class="col-8 p-0">
                                                               <div class="owner-confilg">
                                                                  <p>Convinient time to visit property : Owner will be available {{$propertyList->property->convenient_time}}</p>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </form>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                        </div>
                        @endforeach
                        @endif
                        <hr class="hr-line-space">
                        <div class="accordin-first olddesign_accordien">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="open-tag">
                                    <p class="mb-0 pt-0">
                                       <a class="details-accordin d-inline-block" data-toggle="collapse" href="#collapseCustomerDetails" role="button" aria-expanded="true" aria-controls="collapseCustomerDetails">
                                          Customer Details
                                       </a>
                                    <div class="collapse show" id="collapseCustomerDetails">
                                       <div class="card card-body">
                                          <!--mobile view-->
                                          <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                             <div class="col-12 mb-3">
                                                <img src="{{$data->customer->PicturePath}}" alt="proimg" onerror="this.src='{{$data->customer->ErrorPicturePath}}'" width="100px" height="100px" />
                                             </div>
                                             <div class="col-md-6 mobileInline_6">
                                                <div class="col">
                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span>Name</span>
                                                      <span class="turnicate1 grey w-50 text-left"> {{$data->customer->name}}</span>
                                                   </div>
                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span>Email</span>
                                                      <span class="grey w-50 text-left"> {{$data->customer->email}} </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <!--mobile view end-->
                                          <div class="form-row d-none d-lg-flex">
                                             <div class="col-md-6 right-border ntr">
                                                <div class="col headImg">
                                                   <img src="{{$data->customer->PicturePath}}" alt="proimg" onerror="this.src='{{$data->customer->ErrorPicturePath}}'" width="100px" height="100px" />
                                                </div>
                                                <div class="col">
                                                   <div class="form-group td-tag">
                                                      Name
                                                   </div>
                                                   <div class="form-group td-tag">
                                                      Email
                                                   </div>
                                                </div>
                                                <div class="col">
                                                   <div class="form-group bio-tag">
                                                      {{$data->customer->name}}
                                                   </div>
                                                   <div class="form-group bio-tag">
                                                      {{$data->customer->email}}
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

                        @if(isset($data->payment))
                        <div class="accordin-first olddesign_accordien">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="open-tag">
                                    <p class="mb-0 pt-0">
                                       <a class="details-accordin d-inline-block" data-toggle="collapse" href="#collapseTransactionDetails" role="button" aria-expanded="true" aria-controls="collapseTransactionDetails">
                                          Transaction Details
                                       </a>
                                    <div class="collapse show" id="collapseTransactionDetails">
                                       <div class="card card-body">
                                          <!-- mobile view -->
                                          <div class="form-row d-flex d-lg-none mobileView_dashdata">
                                             <div class="col-md-6 mobileInline_6">
                                                <div class="col">
                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span>Transaction ID</span>
                                                      <span class="grey w-50 text-left"> {{isset($data->payment->transaction_id) ? $data->payment->transaction_id : 'N/A'}}</span>
                                                   </div>
                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span>Amount Paid</span>
                                                      <span class="turnicate1 grey w-50 text-left">
                                                         {{isset($data->payment->amount) ? numberformatWithCurrency($data->payment->amount) : 'N/A'}}
                                                      </span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <div class="col">
                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span> Payment Mode</span>
                                                      <span class="turnicate1 grey w-50 text-left"> {{isset($data->payment->method) ? strtoupper($data->payment->method) : 'N/A'}} </span>
                                                   </div>

                                                   <div class="form-group td-tag d-flex justify-content-between align-items-start">
                                                      <span> Payment Date </span>
                                                      <span class="grey w-50 text-left"> {{isset($data->payment->created_at) ? get_date_week_month_name($data->payment->created_at) : 'N/A'}} </span>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <!-- mobile view end-->

                                          <div class="form-row d-none d-lg-flex">
                                             <div class="col-md-6 right-border ntr">
                                                <div class="col">
                                                   <div class="form-group td-tag">
                                                      Transaction ID
                                                   </div>
                                                   <div class="form-group td-tag">
                                                      Amount Paid
                                                   </div>
                                                </div>
                                                <div class="col">
                                                   <div class="form-group bio-tag">
                                                      {{isset($data->payment->transaction_id) ? $data->payment->transaction_id : 'N/A'}}
                                                   </div>
                                                   <div class="form-group bio-tag">
                                                      {{isset($data->payment->amount) ? numberformatWithCurrency($data->payment->amount) : 'N/A'}}
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-md-6 ntr">
                                                <div class="col">
                                                   <div class="form-group td-tag">
                                                      Payment Mode
                                                   </div>
                                                   <div class="form-group td-tag">
                                                      Payment Date
                                                   </div>
                                                </div>
                                                <div class="col">
                                                   <div class="form-group">
                                                      {{isset($data->payment->method) ? strtoupper($data->payment->method) : 'N/A'}}
                                                   </div>
                                                   <div class="form-group">
                                                      {{isset($data->payment->created_at) ? get_date_week_month_name($data->payment->created_at) : 'N/A'}}
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
                        @endif

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

                        @if(!in_array($data->status ,['completed','rejected']))
                        @if($data->schedule_visit_cancelled_date)
                        <div class="cancle-details fright "><button class="profile-delete">Cancelled</button></div>
                        @else
                        @if($data->cancel_request_date)
                        <div class="cancle-details fright"><button class="profile-delete">Cancel Request Submitted</button></div>
                        @elseif($data->status != 'cancelled' && $data->CancellationBeforeDate == true && isset($data->payment))
                        <div class="cancle-details fright"><button data-toggle="modal" data-target="#scheduleCancelledMod" class="profile-delete">Cancel Booking</button></div>
                        @endif
                        @endif
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('modalSection')
<!-- Modal -->
<div class="modal fade" id="scheduleCancelledMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Reason For Cancellation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         {!! Form::open(['method' => 'post','route' => ['customer.dashboard.myvisits.cancellVisitRequest'],'id'=>'F_cancelBooking']) !!}
         <div class="modal-body ermsg form-group">
            <textarea id="description" required name="description" class="form-control" rows="4" cols="55" placeholder="Enter reason for cancellation"></textarea>
            <input type="hidden" name="slug" value="{{$data->slug}}">
            <input type="hidden" name="actionName" value="ScheduleVisit">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary directSubmit" id="cancelBooking">Submit</button>
         </div>
         {{ Form::close() }}
      </div>
   </div>
</div>
@endsection
@section('uniquePageScript')
<script async src="https://static.addtoany.com/menu/page.js"></script>
<script src="{{URL::to('theme/libs/fotorama/fotorama.js')}}"></script>
@endsection