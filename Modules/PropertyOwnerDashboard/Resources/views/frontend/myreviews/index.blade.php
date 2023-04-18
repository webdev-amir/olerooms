@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "My Reviews".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="my_booking">
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
                           <li class=" active"> My Reviews </li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('propertyownerdashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-9 visits-content">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1">
                  <h2 class="title-bar">
                     <span>My Reviews</span>
                  </h2>
                  <div class="myReview">
                     @include('propertyownerdashboard::frontend.myreviews.ajax_myreviews')
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</div>
@endsection
@section('modalSection')
<!-- modal -->
<div class="modal fade resetpassword_success" id="reporttoadmin" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <h4 class="font24 black medium">Report To admin</h4>
            <div class="form-group mT30">
               <textarea class="form-control" placeholder="Report To admin" name="" rows="4" cols="40"></textarea>
            </div>
            <div class="d-flex justify-content-center  mt-4">
               <a href="#" class="btn customBtn btn-success minw-184">Submit</a>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection