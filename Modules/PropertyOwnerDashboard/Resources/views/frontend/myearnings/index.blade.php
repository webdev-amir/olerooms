@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "My Earnings".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="my_earnings">
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
                           <li>
                              <a href="{{route('vendor.dashboard')}}" title="My Dashboard"> My Dashboard</a>
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                 <path fill="none" d="M0 0h24v24H0z"></path>
                                 <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path>
                              </svg>
                           </li>
                           <li class=" active">My Earnings</li>
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
                  <h2 class="title-bar mb-0"> </h2>
                  <div class="row">
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                        <div class="dasboard_box text-center">
                           <p class="black" id="totalEarnings"> ₹0 </p>
                           <span class="font18 regular grey"> Total Earnings </span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                        <div class="dasboard_box text-center">
                           <p class="orangeshade" id="totalBookings"> 0</p>
                           <span class="font18 regular grey"> Total Bookings </span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="selected fadeInUp animated2 delay1 ">
                  <div class="row booking-flex">
                     <div class="col-md-12 booking-flex-col">
                        <div class="col-md-12 col-lg-4">
                           <h2 class="title-bar">
                              <span>
                                 My Earnings
                              </span>
                           </h2>
                        </div>
                        <div class="col-md-12 col-lg-8 pr-0">
                           <div class="booking-cal">
                              <div class="form-group select-icon drop-booking">
                                 <img src="{{asset('images/arrow-ri.svg') }}" class="select-arrow">
                                 {!! Form::select('property_type', [''=> 'All Properties']+$propertyList, null, ['class' => 'form-control filter_record','onchange'=>"serach();"]) !!}
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
               <div class="tablebtn_list curve">
                  <ul>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='all' || $type=='') active @endif" data-default="all"> All Payment </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='upi' || $type=='') active @endif" data-default="upi"> UPI Payment </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='card') active @endif" data-default="card"> Card Payment </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='wallet') active @endif" data-default="wallet"> Wallet Payment </a></li>
                     <li><a href="javascript:;" class="active_remove_tab @if($type=='netbanking') active @endif" data-default="netbanking"> Netbanking </a></li>
                  </ul>
               </div>
               <div class="user-profile-lists booking-selector">
                  <div class="inner_content w-100">
                     <div class="table-responsive customtable_responsive br30" id="result">
                        @include('propertyownerdashboard::frontend.myearnings.ajax_myearnings')
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection