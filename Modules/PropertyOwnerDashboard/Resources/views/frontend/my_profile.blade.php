@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "My Profile".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="customer_profile">
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
                           <li class=" active"> My Profile </li>
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
                     <span> My Profile </span>
                  </h2>
                  <div class="user-profile-lists profile-clone">
                     <div class="inner_content w-100">
                        {!! Form::model(Auth::user(),['method'=>'post', 'route' => ['vendor.dashboard.updateProfile'], 'class'=>'editProfile','id'=>'F_edit_user']) !!}
                        {{ Form::hidden('image',null, ['id'=>'f_UImage']) }}
                        {{ Form::hidden('logo_image',null, ['id'=>'f_logo_image']) }}
                        {{ Form::hidden('id',null, ['id'=>'id']) }}
                        <div class="profile-setup">
                           <div class="w-100">
                              <div class="selected fadeInUp animated2 delay1">
                                 <div class="row upload_img">
                                    <div class="col-sm-12 col-lg-6">
                                       <div class="logo dashboard-user-img">
                                          <div class="avatar avatar-cover">
                                             <img src="{{auth()->user()->PicturePath}}" alt="proimg" id="v_UImage" onerror="this.src='{{auth()->user()->ErrorPicturePath}}'" />
                                             <div class="upload-btn-wrapper">
                                                <button class="btn">
                                                   <i class="ri-upload-line"></i>
                                                </button>
                                                <input type="file" id="UImage" name="UImage" accept="image/*" class="onlyimageupload" data-uploadurl="{{route('vendor.dashboard.uploadProfile')}}" data-userid="{{auth()->user()->id}}">
                                             </div>
                                          </div>
                                          <p class="upload-txt mt-4">Upload Profile Photo</p>
                                       </div>

                                       {{--

                                       <div class="logo dashboard-user-img logoUpload">
                                          <div class="avatar avatar-cover">
                                             <img src="{{auth()->user()->ComponyLogo}}" alt="proimg" id="v_logo_image" onerror="this.src='{{auth()->user()->ErrorPicturePath}}'" />
                                       <div class="upload-btn-wrapper">
                                          <button class="btn">
                                             <i class="ri-upload-line"></i>
                                          </button>
                                          <input type="file" id="logo_image" name="logo_image_file" accept="image/*" class="onlyimageupload" data-uploadurl="{{route('vendor.uploadSelfyAndLogo')}}">
                                       </div>
                                    </div>
                                    <p class="upload-txt mt-4">Upload Logo</p>
                                 </div>
                                 --}}

                              </div>
                           </div>
                           <div class="row">
                              <div class="col-sm-12 col-lg-10 col-xl-5">
                                 <div class="form-group ermsg">
                                    {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>'Owner Name','title'=>'Please enter owner name','maxlength'=>'50','autocomplete'=>'off']) }}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-sm-12 col-lg-10 col-xl-5">
                                 <div class="form-group">
                                    {{ Form::text('email',null, ['class'=>'form-control','id'=>'email','placeholder'=>'Email Address','title'=>'Please enter email address','maxlength'=>'150','readonly','disabled','autocomplete'=>'off']) }}
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-sm-12 col-lg-10 col-xl-5">
                                 <div class="form-group">
                                    {{ Form::text('phone',null, ['readonly','disabled','maxlength'=>'10','class'=>'form-control isinteger','id'=>'phone','placeholder'=>'Mobile Number','title'=>'Please enter phone number','autocomplete'=>'off']) }}
                                 </div>
                              </div>
                           </div>

                           <div class="row">
                              <div class="col-sm-12 col-lg-10 col-xl-5">
                                 <div class="form-group ermsg">
                                    {{ Form::text('dob',old('dob',auth()->user()->dob ? display_dob_date(auth()->user()->dob) :''), ['required','class'=>'form-control date-picker-dob-update','id'=>'dob','title'=>'Please enter D.O.B.','placeholder'=>'MM/DD/YYYY','autocomplete'=>'off']) }}
                                 </div>
                              </div>
                           </div>

                           <div class="row mt-2">
                              <div class="col-sm-12 col-md-4 col-lg-6 col-xl-3">
                                 <button type="submit" id="edit_user" class="btn customBtn btn-success w-100 mb-3 form-submit directSubmit" data-loader="Please wait updating your details..">Update Profile</button>
                              </div>
                              <div class="col-sm-12 col-md-4 col-lg-6 col-xl-3">
                                 <button type="button" class="outlineBtn_gradient w-100 mb-3" data-toggle="modal" data-target="#deleteAcoount">
                                    Delete Account
                                 </button>
                              </div>
                              <div class="col-sm-12 col-md-4 col-lg-6 col-xl-3">
                                 <button type="button" class="outlineBtn_gradient w-100" data-toggle="modal" data-target="#deactivateAccount">
                                    Deactivate Account
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  {!! Form::close() !!}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
@include('propertyownerdashboard::frontend.delete_account_model')
@include('propertyownerdashboard::frontend.deactivate_account_model')
@endsection