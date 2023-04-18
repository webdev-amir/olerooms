@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "My Bookings".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="manage_my_booking">
   <div class="container-fluid">
      <div class="row row-eq-height">
         <div class="col-md-3 slide-menu">
            @include('propertyownerdashboard::includes.sidebar_profile_menu')
         </div>
         <div class="col-md-9 top-menu">
            <div class="user-form-settings">
               <div>
                  <div class="dash_header d-flex justify-content-between">
                     <div aria-label="breadcrumb" class="breadcrumb-page-bar">
                        <ul class="page-breadcrumb p-0">
                           <li class=" active"> My Bookings </li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('propertyownerdashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9 booking-style">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1 ">
                  <div class="row booking-flex">
                     <div class="col-md-12 booking-flex-col d-block">
                        <div class="col-md-12 col-lg-4">
                           <h2 class="title-bar">
                              <span>
                              My Bookings
                              </span> 
                           </h2>
                        </div>
                        <div class="col-md-12 col-lg-8">
                           <div class="booking-cal">
                              <div class="form-group select-icon drop-booking">
			                        <img src="{{asset('images/arrow-ri.svg') }}" class="select-arrow">
			                        <select class="form-control filter_record" name="sort_by" onchange="serach();">
			                           <option value="">Type Properties</option>
			                           @if(isset($propertyTypes))
			                           	@foreach($propertyTypes as $propertyTypeList)
			                           		<option value="{{$propertyTypeList->id}}">{{$propertyTypeList->name}}</option>
			                           	@endforeach
			                           @endif
			                        </select>
			                     </div>
                              <div class="form-group select-icon drop-booking w-auto pr-0">
                                 <div id="reportrange" class="cal-new" style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 100%">
                                     <i class="fa fa-calendar"></i>&nbsp;
                                     <span></span> &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                 </div>
                                 {{ Form::hidden('from',@$GET['from'], ['id'=>'start_date']) }}
                                 {{ Form::hidden('to',@$GET['to'], ['id'=>'end_date']) }}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tablebtn_list curve">
                  <ul>
                     <li><a href="#" name="booking_type"> All Booking </a></li>
                     <li><a href="#" name="booking_type"> Past bookings </a></li>
                     <li><a href="#" name="booking_type"> Upcoming Booking </a></li>
                     <li><a href="#" name="booking_type"> Cancelled </a></li>
                  </ul>
               </div>
               <div class="user-profile-lists booking-selector">
                  <div class="inner_content w-100">
                     <div class="table-responsive  customtable_responsive br30">
                       @include('booking::frontend.my_bookings.get_ajax_tabledata')
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection