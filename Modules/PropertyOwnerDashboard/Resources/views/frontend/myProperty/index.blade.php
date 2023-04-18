@extends('propertyownerdashboard::layouts.dashboard_master')
@section('title', "All Property".trans('menu.pipe')." " .app_name())
@section('content')
<div class="bravo_user_profile" id="manage_my_property">
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
                           <li class=" active"> My Property </li>
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
               <div class="selected fadeInUp animated2 delay1">
                  <div class="row booking-flex">
                     <div class="col-md-12 booking-flex-col booking-flex-mobile">
                        <div class="col-sm-12 col-md-3 col-lg-3 col-xxl-4">
                           <h2 class="title-bar">
                              <span> My Properties </span>
                           </h2>
                        </div>
                        <div class="col-md-12 col-lg-9 pr-0 col-xxl-8">
                           <div class="booking-cal">
                              <div class="form-group select-icon drop-booking">
                                 <img src="{{asset('images/arrow-ri.svg') }}" class="select-arrow">
                                 <select class="form-control filter_record" name="property_type" onchange="serach();">
                                    <option value="">All Properties</option>
                                    @if(isset($propertyTypes))
                                    @foreach($propertyTypes as $propertyTypeList)
                                    <option value="{{$propertyTypeList->slug}}">{{$propertyTypeList->name}}</option>
                                    @endforeach
                                    @endif
                                 </select>
                              </div>
                              <div class="form-group select-icon drop-booking w-auto pr-0" id="filter-with-daterange">
                                 <div id="reportrange" class="cal-new" style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> &nbsp;&nbsp;<i class="fa fa-caret-down"></i>
                                 </div>
                                 {{ Form::hidden('from',@$GET['from'], ['id'=>'start_date']) }}
                                 {{ Form::hidden('to',@$GET['to'], ['id'=>'end_date']) }}
                              </div>
                              <div class="mobile_inline d-flex">
                                 <div class="addPropertybtn ml-3 position-relative">
                                    <a class="btn btn-success property-btn" href="{{route('manageProperty.create')}}">
                                       Add Property
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="user-profile-lists">
                  <div class="inner_content w-100">
                     <div class="table-responsive  customtable_responsive br30" id="result">
                        @include('propertyownerdashboard::frontend.myProperty.ajax_all_myproperty')
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('modalSection')

<!-- Modals -->
<div class="modal fade resetpassword_success upload_selfie_agreement" id="upload_selfie" tabindex="-1" role="dialog" aria-hidden="true">
   {!! Form::open(['route' => 'vendor.myproperty.uploadSelfie.save','class'=>'','id'=>'F_UploadSelfieData','autocomplete'=> 'off']) !!}
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <button type="button" class="close" data-dismiss="modal" title="Close">&times;</button>
            <h4 class="font24 black medium"> Upload Selfie </h4>
            <div class="form-group mT30">
               <div class="uploadrow ermsg">
                  <div class="uploadImages_block">
                     <div class="uploadSinglefile" id="cover_image_files">
                        <div class="uploadfileBtn">
                           <input type="file" id="UploadSelfie" class="onlyimageupload" data-uploadurl="{{route('vendor.myproperty.uploadSelfie')}}" />
                           <i class="ri-upload-line d-block"></i>
                           Upload Selfie
                        </div>
                     </div>
                  </div>
                  {{ Form::hidden('upload_selfie',null, ['required','id'=>'f_UploadSelfie','class'=>'cover_image_files','title'=>'Please upload Selfie Image']) }}
                  {{ Form::hidden('property_id',null, ['id'=>'property_id']) }}
               </div>
            </div>
            <div class="d-flex justify-content-center  mt-4">
               <button class="btn customBtn btn-success minw-184 directSubmit" id="UploadSelfieData">Submit</button>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>

<div class="modal fade resetpassword_success upload_selfie_agreement" id="upload_agrement" tabindex="-1" role="dialog" aria-hidden="true">
   {!! Form::open(['route' => 'vendor.myproperty.uploadAgreement.save','class'=>'','id'=>'F_UploadAgreementData','autocomplete'=> 'off']) !!}
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <button type="button" class="close" data-dismiss="modal" title="Close">x</button>
            <h4 class="font24 black medium"> Upload Agreement </h4>
            <div class="form-group mT30 ermsg">
               <div class="uploadImages_block">
                  <div class="uploadSinglefile" id="image_files">
                     <div class="uploadfileBtn">
                        <input type="file" id="UploadAgreement" class="imageandpdfupload" data-uploadurl="{{route('vendor.myproperty.uploadAgreement')}}" name="UploadAgreement" />
                        <i class="ri-upload-line d-block"></i>
                        Upload Agreement
                     </div>
                     <div id="ht_UploadAgreement"></div>
                  </div>
               </div>
               {{ Form::hidden('upload_agreement',null, ['required','id'=>'f_UploadAgreement','class'=>'image_files','title'=>'Please upload Document or Agreement']) }}
               {{ Form::hidden('property_id',null, ['id'=>'property_id']) }}
            </div>
            <div class="d-flex justify-content-center  mt-4">
               <button class="btn customBtn btn-success minw-184 directSubmit" id="UploadAgreementData">Submit</button>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>

<div class="modal fade resetpassword_success" id="deleteProperty" tabindex="-1" role="dialog" aria-hidden="true">
   {!! Form::open(['route' => 'vendor.myproperty.delete','class'=>'','id'=>'F_UploadAgreementData','autocomplete'=> 'off']) !!}
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design">
         <div class="modal-body text-center p-0">
            <figure class="modalicon_wrap">
               <i class="ri-delete-bin-line"></i>
            </figure>
            <h4 class="font24 black medium mb-3">Delete Property</h4>
            <p class="font16 grey regular">
               Do you wish to Delete this Property
            </p>
            <div class="d-flex justify-content-center mt-3">
               {{ Form::hidden('property_id',null, ['id'=>'property_id']) }}
               <button class="outlineBtn_gr0adient minw-184 mr-3 directSubmit">Yes , Delete it</button>
               <a href="javascript:;" class="outlineBtn_green minw-101" data-dismiss="modal" aria-label="Close">No</a>
            </div>
         </div>
      </div>
   </div>
   {!! Form::close() !!}
</div>

<div class="modal fade resetpassword_success" id="applyOffer" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content relative modal_design p-0">
         <div class="modal-body text-center p-0">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="font24 black medium mb-1 mT30"> Select One Offer </h4>
            <div id='loader' style='display: none;'>
               <img src="{{ asset('images/loader.gif') }}" width='32px' height='32px'>
            </div>
            <div class="applyOffer_list">

            </div>
         </div>
      </div>
   </div>
</div>
@endsection
