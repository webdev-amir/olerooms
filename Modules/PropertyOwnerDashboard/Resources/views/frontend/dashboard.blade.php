@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "Dashboard".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="propDashboard">
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
                           <li class=" active"> My Dashboard </li>
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
               <div class="dashboardBoxes_row fadeInUp animated2 delay2 selected">
                  <h2 class="title-bar mb-0"> Welcome </h2>
                  <div class="row">
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3" title="Total Earnings">
                        <div class="dasboard_box text-center">
                           <p class="orangeshade"> {{$records['totalEarnings']}} </p>
                           <span class="font18 regular grey"> Total Earnings </span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3" title="Total Bookings">
                        <div class="dasboard_box text-center">
                           <p class="orangeshade">{{$records['totalBookings']}}</p>
                           <span class="font18 regular grey"> Total Bookings </span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3" title="Available Seats">
                        <div class="dasboard_box text-center">
                           <p class="orangeshade"> {{$records['availableSeats']}}</p>
                           <span class="font18 regular grey"> Available Seats </span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                        <div class="dasboard_box text-center" title="Current Bookings">
                           <p class="orangeshade"> {{$records['currentBookings']}} </p>
                           <span class="font18 regular grey"> Current Bookings </span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="mt-2">
                  @if(auth()->user()->property->count()== 0)
                  <div class="addproperty d-flex align-items-center justify-content-center" title="Add New Property">
                     <a href="{{route('manageProperty.create')}}"><i class="ri-add-line"></i></a>
                     <span class="font16 medium black d-block mt-3"> Add New Property </span>
                  </div>
                  @endif
               </div>
               <div id="result">
                  @include('propertyownerdashboard::frontend.my_bookings.ajax_booking_requests')
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
@endsection