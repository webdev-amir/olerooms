@extends('mydashboard::layouts.dashboard_master')
@section('title', "My Bookings".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="customer_profile">
   <div class="container-fluid">
      <div class="row row-eq-height">
         <div class="col-md-3 slide-menu">
            @include('mydashboard::includes.sidebar_profile_menu')
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
                     @include('mydashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9 booking-style">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1 ">
                  <div class="row booking-flex">
                     <div class="col-md-12 booking-flex-col d-block">
                        <div class="row">
                           <div class="col-md-3 col-lg-4 ">
                              <h2 class="title-bar">
                                 <span>
                                    My Bookings
                                 </span>
                              </h2>
                           </div>
                           <div class="col-md-9 col-lg-8 pr-0">
                              <div class="booking-cal">
                                 <div class="form-group select-icon drop-booking">
                                    <img src="{{asset('images/arrow-ri.svg') }}" class="select-arrow">
                                    <select class="form-control filter_record" name="property_type" onchange="serach();">
                                       <option value="">Property Type</option>
                                       @if(isset($propertyTypes))
                                       @foreach($propertyTypes as $propertyTypeList)
                                       <option value="{{$propertyTypeList->slug}}">{{$propertyTypeList->name}}</option>
                                       @endforeach
                                       @endif
                                    </select>
                                 </div>
                                 <div class="form-group select-icon drop-booking w-auto pr-0" id="filter-with-daterange">
                                    <div id="reportrange" class="cal-new " style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 100%">
                                       <i class="fa fa-calendar"></i>&nbsp;
                                       <span></span> &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                    </div>
                                    {{ Form::hidden('from',@$GET['from'], ['id'=>'start_date']) }}
                                    {{ Form::hidden('to',@$GET['to'], ['id'=>'end_date']) }}
                                    {{ Form::hidden('search_type',@$type, ['id'=>'search_type']) }}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tablebtn_list curve">
                  <ul>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='all' || $type=='') active @endif" data-default="all"> All Booking </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='active') active @endif" data-default="active"> Today </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='upcoming') active @endif" data-default="upcoming"> Upcoming </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='confirmed') active @endif" data-default="confirmed"> Confirmed </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='completed') active @endif" data-default="completed"> Completed </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='cancelled') active @endif" data-default="cancelled"> Cancelled </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='rejected') active @endif" data-default="rejected"> Rejected </a></li>
                  </ul>
               </div>
               <div class="user-profile-lists booking-selector">
                  <div class="inner_content w-100">
                     <div class="table-responsive  customtable_responsive br30" id="result">
                        @include('mydashboard::manageBooking.ajax_all_mybooking')
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
@include('mydashboard::includes.review_modal')
@endsection