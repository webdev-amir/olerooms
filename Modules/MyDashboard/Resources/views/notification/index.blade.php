@extends('mydashboard::layouts.dashboard_master')
@section('title', "All Notifications".trans('menu.pipe')." " .app_name())
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
                           <li class=" active"> My Notifications </li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('mydashboard::includes.sidebar_top_header_menu')
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
                           <div class="" id="result_all_notification">
                              	@include('mydashboard::notification.ajax_all_notifics')
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
</div>

@endsection

