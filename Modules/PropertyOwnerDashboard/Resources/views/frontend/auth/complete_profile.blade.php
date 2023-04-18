@extends('propertyownerdashboard::layouts.master')
@section('title', "Complete Your Profile".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content" id="vendor_complete_profile">
   <section class=" bravo-list-tour authSection padding50">
      <div class="container">
         <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6  fadeInUp animated1 selected">
               <div class="heading-title mb-5 medium">
                  Welcome To <span class="green">OLE Rooms!</span>
                  <p class="simplified-sub mt-2"> Complete your profile </p>
               </div>
               <div class="customForm">
                  {!! Form::open(['route' => 'vendor.submitCompleteProfileVerification','class'=>'form form-loader','id'=>'F_completeprofile','autocomplete'=> 'off']) !!}
                  <div class="form-group ermsg">
                     {{ Form::text('aadhar_card_number',null, ['required','class'=>'numberonly form-control','id'=>'aadhar_card_number','placeholder'=>'Aadhar Card Number*','title'=>'Please enter aadhar card number','maxlength'=>'12','minlength'=>'12']) }}
                  </div>
                  <div class="form-group ermsg">
                     <label class="font16 grey">Upload Aadhaar Card</label>
                     <div class="uploadImages_block">
                        <div class="uploadfiles" id="aadharcardfile">
                           <div class="uploadfileBtn">
                              <input type="file" id="adhar_card_doc" class="imageandpdfupload" data-uploadurl="{{route('vendor.uploadUserImagePdf')}}" />
                              <i class="ri-upload-line"></i>
                              Aadhaar Card
                           </div>
                        </div>
                        {{ Form::hidden('adhar_card_doc',null, ['id'=>'f_adhar_card_doc','class'=>'aadharcardfile','title'=>'Please upload aadhar card proof']) }}
                     </div>
                  </div>
                  <div class="form-group ermsg">
                     {{ Form::text('gst_number',null, ['class'=>'form-control','id'=>'gst_number','placeholder'=>'GST Number','title'=>'Please enter GST number','maxlength'=>'15','minlength'=>'15']) }}
                  </div>
                  <div class="form-group">
                     <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                           <div class="uploadrow ermsg">
                              <label class="font16 grey">Upload Selfie</label>
                              <div class="uploadImages_block ">
                                 <div class="uploadfiles" id="selfyfile">
                                    <div class="uploadfileBtn">
                                       <input type="file" id="selfy_image" class="onlyimageupload" data-uploadurl="{{route('vendor.uploadSelfyAndLogo')}}" />
                                       <i class="ri-upload-line"></i>
                                       Upload Selfie Image
                                    </div>
                                 </div>
                                 {{ Form::hidden('selfy_image',null, ['id'=>'f_selfy_image','class'=>'selfyfile','title'=>'Please upload selfy']) }}
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                           <div class="uploadrow ermsg">
                              <label class="font16 grey">Upload logo</label>
                              <div class="uploadImages_block">
                                 <div class="uploadfiles" id="logofile">
                                    <div class="uploadfileBtn">
                                       <input type="file" id="logo_image" class="onlyimageupload" data-uploadurl="{{route('vendor.uploadSelfyAndLogo')}}" />
                                       <i class="ri-upload-line"></i>
                                       Upload Logo
                                    </div>
                                 </div>
                                 {{ Form::hidden('logo_image',null, ['id'=>'f_logo_image','class'=>'logofile','title'=>'Please upload logo']) }}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group text-right mb-0  mt-5">
                     <button type="submit" id="completeprofile" class="btn customBtn btn-success minw-184 form-submit directSubmit"> Submit </button>
                  </div>
                  </form>
               </div>
            </div>
            
            <div class="col-sm-12 col-md-12 col-lg-6">
               <div class="authContent">
                  <div class="fadeInUp animated1 selected">
                     <h4 class="green mb-2">How is Olerooms <span class="d-block black">different?</span></h4>
                     <p>Monthly 15 lakh people visit Olerooms in search of houses on rent.</p>
                  </div>
                  <div class="iconList mt-4">
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="ri-home-smile-line"></i></figure>
                        <div class="content">
                           <p> Guided House Visits</p>
                           <span>We give guided tour of your house to interested tenants</span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="ri-timer-line"></i></figure>
                        <div class="content">
                           <p> Rent on Time</p>
                           <span>We guarantee rent on time every month</span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="ri-file-3-line"></i></figure>
                        <div class="content">
                           <p> Zero Paperwork</p>
                           <span>We do the paperwork for you like agreement creation </span>
                        </div>
                     </div>
                     <div class="iconBox d-flex">
                        <figure class="mb-0 mr-4"><i class="ri-file-shield-line"></i></figure>
                        <div class="content">
                           <p> House Safety </p>
                           <span>We ensure to keep your house in good condition</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection