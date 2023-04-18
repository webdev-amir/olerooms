@extends('company::layouts.dashboard_master')
@section('title', "All Notifications".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="dashboard">
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
                           <li>
                             <a href="{{route('vendor.dashboard')}}" title="My Dashboard"> My Dashboard</a> 
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"></path>
                             </svg>
                           </li>
                           <li class=" active"> My Notifications </li>
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
					<div class="selected fadeInUp animated2 delay1">
						<h2 class="title-bar">
							<span>Notifications</span> 
						</h2>
						<div class="user-profile-lists">
							<div class="inner_content w-100">
								<div class="notification-setup">
                           <div id="result_all_notification">
                              	@include('company::frontend.notification.ajax_all_notifics')
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