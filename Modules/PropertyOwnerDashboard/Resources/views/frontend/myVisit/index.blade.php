@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "My Visits".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="my_wishlist_page">
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
                                    <li class=" active"> My Visits </li>
                                </ul>
                                <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                            </div>
                            @include('propertyownerdashboard::includes.sidebar_top_header_menu')
                        </div>
                    </div>
                </div>
            </div>
             {{ Form::hidden('search_type',@$type, ['id'=>'search_type']) }}
            <div class="col-md-9 visits-content">
               <div class="user-form-settings">
                  <div class="selected fadeInUp animated2 delay1 ">
                     <h2 class="title-bar">
                        <span>
                        My Visits
                        </span> 
                     </h2>
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
                                  @include('propertyownerdashboard::frontend.myVisit.ajax_all_myvisit')
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