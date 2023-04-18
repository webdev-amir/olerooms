@extends('layouts.dashboard_master')
@section('title', "Notification".trans('menu.pipe')." " .app_name())
@section ('content')
<div class="bravo_user_profile">
    <div class="container-fluid">
       <div class="row row-eq-height">
         <div class="col-md-3">
            @include('mydashboard::includes.sidebar_profile_menu')
         </div>
         <div class="col-md-9">
            <div class="user-form-settings">
                <div class="breadcrumb-page-bar" aria-label="breadcrumb">
                  <ul class="page-breadcrumb">
                     <li class="">
                        <a href="{{URL::to('/')}}"><i class="fa fa-home"></i> Home</a>
                        <i class="fa fa-angle-right"></i>
                     </li>
                     <li class=" active">
                        Notification
                     </li>
                  </ul>
                  @include('includes.notification-dashboard')
                  <div class="bravo-more-menu-user">
                     <i class="icofont-settings"></i>
                  </div>
               </div>
               <h2 class="title-bar no-border-bottom">
                     All Notifications
               </h2> 
                <link href="{{URL::to('theme\dist\frontend\css\notification.css')}}" rel="stylesheet">
                <div id="bravo_notify">
                    <div class="container my-5">
                        <div class="row">
                            <div class="col-3 noti-menu">
                                <div class="panel">
                                    <ul class="dropdown-list-items p-0">
                                        <li class="notification @if(empty($type)) active @endif">
                                            <i class="fa fa-inbox fa-lg mr-2"></i> <a href="{{route('notification.index')}}">&nbsp;All</a>
                                        </li>
                                        <li class="notification @if(!empty($type) && $type == 'unread') active @endif">
                                            <i class="fa fa-envelope-o fa-lg mr-2"></i> <a href="{{route('notification.index',['type'=>'unread'])}}">Unread</a>
                                        </li>
                                        <li class="notification @if(!empty($type) && $type == 'read') active @endif">
                                            <i class="fa fa-envelope-open-o fa-lg mr-2"></i> <a href="{{route('notification.index',['type'=>'read'])}}">Read</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @include('notifications::include.list')
                        </div>
                    </div>
                </div>
            </div>  
         </div>
       </div>
    </div>
</div>
@endsection
