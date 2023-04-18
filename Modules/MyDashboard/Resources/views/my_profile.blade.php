@extends('mydashboard::layouts.dashboard_master')
@section('title', "My Profile".trans('menu.pipe')." " .app_name())
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
                           <li class=" active"> My Profile </li>
                        </ul>
                        <div class="bravo-more-menu-user"><i class="fa fa-bars"></i></div>
                     </div>
                     @include('mydashboard::includes.sidebar_top_header_menu')
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-9 visits-content" id="customer_profile_tab">
            <div class="user-form-settings">
               <div class="selected fadeInUp animated2 delay1">
                  <h2 class="title-bar">
                     <span>
                        My Profile
                     </span>
                  </h2>
                  <div class="user-profile-lists profile-clone">
                     <div class="inner_content w-100">
                        <div class="profile-setup">
                           <div class="tab">
                              <button class="tablinks" data-tabname="profile" id="defaultOpen">
                                 <i class="fa fa-user" aria-hidden="true" class="tag"></i>
                                 My Profile
                              </button>
                              <button class="tablinks" data-tabname="password">
                                 <i class="fa fa-lock" aria-hidden="true" class="tag"></i>
                                 Change Password
                              </button>
                           </div>
                           <div id="profile" class="tabcontent">
                              {!! Form::model(Auth::user(),['method'=>'post', 'route' => ['customer.dashboard.updateProfile'], 'class'=>'editProfile','id'=>'F_edit_user']) !!}
                              {{ Form::hidden('image',null, ['id'=>'f_UImage']) }}
                              {{ Form::hidden('id',null, ['id'=>'id']) }}
                              <div class="selected fadeInUp animated2 delay1">
                                 <div class="upload_img mb-4">
                                    <div class="logo dashboard-user-img">
                                       <div class="avatar avatar-cover">
                                          <img src="{{auth()->user()->PicturePath}}" alt="proimg" id="v_UImage" onerror="this.src='{{auth()->user()->ErrorPicturePath}}'" />
                                          <div class="upload-btn-wrapper">
                                             <button class="btn">
                                                <i class="ri-upload-line"></i>
                                             </button>
                                             <input type="file" id="UImage" name="UImage" accept="image/*" class="onlyimageupload" data-uploadurl="{{route('customer.dashboard.uploadProfile')}}" data-userid="{{auth()->user()->id}}">
                                          </div>
                                       </div>
                                       <p class="upload-txt mt-4">Upload Profile Photo</p>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group ermsg">
                                       {{ Form::text('name',null, ['required','class'=>'form-control','id'=>'name','placeholder'=>'Person Name','title'=>'Please enter person name','maxlength'=>'50','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group select-icon ermsg">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                          <path fill="none" d="M0 0h24v24H0z"></path>
                                          <path d="M12 15l-4.243-4.243 1.415-1.414L12 12.172l2.828-2.829 1.415 1.414z" fill="rgba(27,37,39,1)"></path>
                                       </svg>
                                          {!!Form::select('city',$cityPluck,null,['placeholder'=>"City of Residence",'required','id'=>'city','title'=>'Please select city', 'class' => 'form-control'])!!}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group ermsg">
                                       {{ Form::text('email',null, ['required','class'=>'form-control','id'=>'email','placeholder'=>'Email ID','title'=>'Please enter Email ID','maxlength'=>'50','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group ermsg">
                                    {{ Form::text('dob',old('dob',auth()->user()->dob ? display_dob_date(auth()->user()->dob) :''), ['required','class'=>'form-control date-picker-dob-update','id'=>'dob','title'=>'Please enter D.O.B.','placeholder'=>'MM/DD/YYYY','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group select-icon ermsg">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                          <path fill="none" d="M0 0h24v24H0z"></path>
                                          <path d="M12 15l-4.243-4.243 1.415-1.414L12 12.172l2.828-2.829 1.415 1.414z" fill="rgba(27,37,39,1)"></path>
                                       </svg>
                                       {{ Form::select('gender', array(''=>'Gender')+Config::get('custom.gender-list'),null, ['required','class'=>'form-control','id'=>'gender','title'=>'Please enter gender.','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group select-icon ermsg">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                          <path fill="none" d="M0 0h24v24H0z"></path>
                                          <path d="M12 15l-4.243-4.243 1.415-1.414L12 12.172l2.828-2.829 1.415 1.414z" fill="rgba(27,37,39,1)"></path>
                                       </svg>
                                       {{ Form::select('marital_status', array(''=>'Marital Status')+Config::get('custom.marital-status-list'),null, ['required','class'=>'form-control','id'=>'marital_status','title'=>'Please enter marital status','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group select-icon ermsg">
                                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                          <path fill="none" d="M0 0h24v24H0z"></path>
                                          <path d="M12 15l-4.243-4.243 1.415-1.414L12 12.172l2.828-2.829 1.415 1.414z" fill="rgba(27,37,39,1)"></path>
                                       </svg>
                                       {{ Form::select('occupation', array(''=>'Select Occupation')+Config::get('custom.occupation-list'),null, ['required','class'=>'form-control','id'=>'occupation','title'=>'Please select occupation','autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group ermsg">
                                       {{ Form::text('phone',null, ['required','class'=>'form-control isinteger','id'=>'phone','placeholder'=>'Mobile Number','title'=>'Please enter mobile number.','minlength'=>10,'maxlength'=>10,'autocomplete'=>'off']) }}
                                    </div>
                                 </div>
                                 <div class="col-sm-6 mobile_OTP_box" style="display: none;">
                                    <div class="form-group otpRow d-flex verification-code ">
                                       <input type="text" maxlength=1 placeholder="1" class="form-control numberonly">
                                       <input type="text" maxlength=1 placeholder="2" class="form-control numberonly">
                                       <input type="text" maxlength=1 placeholder="3" class="form-control numberonly">
                                       <input type="text" maxlength=1 placeholder="4" class="form-control numberonly">
                                    </div>
                                    <div class="form-group ermsg">
                                       <input type="hidden" name="mobile_otp" id="mobile_otp" class="form-control mobile_OTP_input" disabled>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group ermsg">
                                             {{ Form::textarea('address',null, ['required','class'=>'form-control','id'=>'address','title'=>'Please enter full address','autocomplete'=>'off','maxlength'=>250,'rows'=>5,'cols'=>30,'placeholder'=>'Full Addesss']) }}
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="">
                                 <div class="profile-updte-btn profilebtnissue_solved">
                                       <button type="submit" id="edit_user" class="profile-update form-submit directSubmit" data-loader="@lang('flash.loader.updating_your_profile')">Update Profile</button>
                                       <button type="button" class="outlineBtn_gradient mb-3 ml-2" data-toggle="modal" data-target="#deleteAcoount">Delete Account</button>
                                   
                                 </div>
                              </div>
                              {!! Form::close() !!}
                           </div>
                           <div id="password" class="tabcontent">
                              <div class="selected fadeInUp animated2 delay1">
                                 {!! Form::open(['method'=>'post', 'route' => ['customer.dashboard.updatePassword'],'id'=>'F_changePassword','autocomplete'=>'off']) !!}
                                 {{ Form::hidden('id',auth()->user()->id) }}
                                 <div class="row">
                                    <div class="col-sm-6">
                                       <div class="form-group ermsg">
                                          <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" autocomplete="off" required title="@lang('menu.validiation.please_enter_current_password')" maxlength="80">
                                       </div>
                                       <div class="form-group ermsg">
                                          <input type="password" name="password" id="newpassword" class="form-control" placeholder="New Password" autocomplete="off" required title="@lang('menu.validiation.please_enter_new_password')" maxlength="80">
                                       </div>
                                       <div class="form-group ermsg">
                                          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm New Password" autocomplete="off" required maxlength="80" title="{{trans('menu.validiation.please_enter_confirm_password')}}">
                                       </div>
                                       <button type="submit" id="changePassword" class="directSubmit profile-update" title="{{trans('menu.form.update_password')}}" data-loader="@lang('flash.loader.updating_your_passowrd')">Reset Password</button>
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
      </div>
   </div> 
</div>

@endsection