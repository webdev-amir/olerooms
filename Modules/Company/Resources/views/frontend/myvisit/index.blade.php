@extends('company::layouts.dashboard_master')
@section('title', "My Visits ".trans('menu.pipe')." " .app_name())
@section('section_type_dashboard',"wishlist-section")
@section('content')
<div class="bravo_user_profile" id="my_wishlist_page">
    <div class="container-fluid">
        <div class="row row-eq-height">
            <div class="col-md-3 slide-menu">
                @include('company::includes.sidebar_profile_menu')
            </div>
            <div class="col-md-9 top-menu">
                <div class="user-form-settings">
                    <div>
                        <div class="dash_header d-flex justify-content-between">
                            <div aria-label="breadcrumb" class="breadcrumb-page-bar">
                                <ul class="page-breadcrumb p-0">
                                    <li class=" active"> My Visits </li>
                                </ul>
                                <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                            </div>
                            @include('company::includes.sidebar_top_header_menu')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 visits-content">
               <div class="user-form-settings">
                  <div class="selected fadeInUp animated2 delay1 ">
                    <div class="row booking-flex">
                        <div class="col-md-12 booking-flex-col d-block">
                            <div class="row">
                                <div class="col-md-3 col-lg-4 ">
                                     <h2 class="title-bar">
                                        <span>
                                        My Visits
                                        </span>
                                     </h2>
                                 </div>
                                 <div class="col-md-9 col-lg-8 pr-0">
                                      <div class="booking-cal">
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
                           <li><a href="javascript:;" class="active_remove_tab @if($type=='all' || $type=='') active @endif" data-default="all"> All Visits </a></li>
                           <li><a href="javascript:;" class="active_remove_tab @if($type=='active') active @endif" data-default="active"> Today Visits </a></li>
                           <li><a href="javascript:;" class="active_remove_tab @if($type=='past_visit') active @endif" data-default="past_visit"> Past Visits </a></li>
                           <li><a href="javascript:;" class="active_remove_tab @if($type=='upcoming_visit') active @endif" data-default="upcoming_visit"> Upcoming visits </a></li>
                           <li><a href="javascript:;" class="active_remove_tab @if($type=='cancelled') active @endif" data-default="cancelled"> Cancelled </a></li>
                        </ul>
                        <input type="hidden" name="name" id="myvisit_filter">
                     </div>
                     <div class="user-profile-lists">
                        <div class="inner_content w-100">
                           <div class="">
                              <div class="table-responsive  customtable_responsive br30" id="result">
                                 @include('company::frontend.myvisit.ajax_all_myvisit')
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