@php
$routeName = auth()->user()->hasRole('admin')? 'admin.property.mediaStore':'property.mediaStore';
@endphp


<h4 class="font18 black medium mb-2 mT30">Electricity bill image</h4>
<div class="row">
   <div class="col-md-6">
      <div class="attactmentFile ermsg">
         @if($formData->electricity_bill!= '')
         <a href="{{URL::to('storage/app/public/property/'.$formData->electricity_bill)}}" download>
            <strong> Filename: {{$formData->electricity_bill}}</strong>
         </a>
         <br>
         @endif
         <input type="file" id="EBill" name="EBill" class="form-control imageandpdfupload" data-uploadurl="{{route($routeName ,[$formData->user_id])}}">


         {{ Form::hidden('electricity_bill',null, ['id'=>'f_EBill','title'=>'Please upload electricity bill']) }}
      </div>
   </div>
</div>
<div class="row">
   @if($formData && @$formData->status_selfie == '')
   {{ Form::hidden('status_selfie','pending', []) }}
   @endif
   @if($formData && @$formData->status_selfie == 'rejected')
   @php $formData->upload_selfie = NULL; @endphp
   {{ Form::hidden('status_selfie','pending', []) }}
   @endif
   @if($formData && @$formData->status_selfie == 'approved')
   {{ Form::hidden('upload_selfie',null, ['id'=>'f_upload_selfie','class'=>'selfyfile','title'=>'Please upload selfie']) }}
   @else
   <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="uploadrow ermsg">
         <h4 class="font18 black medium mb-2 mT30"> Upload Selfie Image</h4>
         <div class="uploadImages_block ">
            <div class="uploadSinglefile" id="selfyfile">
               <div class="uploadfileBtn">
                  <input type="file" id="upload_selfie" class="onlyimageupload" data-uploadurl="{{route($routeName,[$formData->user_id])}}" />
                  <i class="ri-upload-line"></i>
                  Upload Selfie
               </div>
            </div>
            @if($formData && @$formData->SelfieImgThunbnail && @$formData->upload_selfie)
            <span class="pip"><img class="imageThumb" src="{{@$formData->SelfieImgThunbnail}}" title="Cover Image" /><br />
               <span class="remove removesingle" data-remove="upload_selfie_files">Remove image</span></span>
            @endif
            {{ Form::hidden('upload_selfie',null, ['required','id'=>'f_upload_selfie','class'=>'selfyfile upload_selfie_files','title'=>'Please upload selfie']) }}
         </div>
      </div>
   </div>
   @endif

   @if($formData && @$formData->status_agreement == '')
   {{ Form::hidden('status_agreement','pending', []) }}
   @endif
   @if($formData && @$formData->status_agreement == 'rejected')
   @php $formData->upload_agreement = NULL; @endphp
   {{ Form::hidden('status_agreement','pending', []) }}
   @endif
   @if($formData && @$formData->status_agreement == 'approved')
   {{ Form::hidden('upload_agreement',null, ['id'=>'f_UploadAgreement','class'=>'image_files','title'=>'Please upload agreement']) }}
   @else
   <div class="col-sm-12 col-md-12 col-lg-6">
      <div class="uploadrow ermsg">
         <h4 class="font18 black medium mb-2 mT30"> Upload Agreement</h4>
         <div class="uploadImages_block ">
            <div class="uploadSinglefile" id="image_files">
               <div class="uploadfileBtn">
                  @if(auth()->user()->hasRole('admin'))
                  <input type="file" id="UploadAgreement" class="imageandpdfupload" data-uploadurl="{{route('admin.manageProperty.uploadAgreement',[$formData->user_id])}}" name="UploadAgreement" />
                  @else
                  <input type="file" id="UploadAgreement" class="imageandpdfupload" data-uploadurl="{{route('manageProperty.uploadAgreement',[$formData->user_id])}}" name="UploadAgreement" />
                  @endif
                  <i class="ri-upload-line"></i>
                  Upload Agreement
               </div>
            </div>

            @if($formData)
            @if (preg_match('/(\.jpg|\.png|\.bmp)$/i', @$formData->upload_agreement))
            <span class="pip"><img class="imageThumb" src="{{@$formData->AgreementImgThunbnail}}" title="Cover Image" /><br />
               <span class="remove removesingle" data-remove="upload_agreement_files">Remove image</span></span>
            @else
            @if($formData->upload_agreement)
            <strong>Filename: {{$formData->upload_agreement}}</strong>
            @endif
            @endif
            @endif
            <div id="ht_UploadAgreement"></div>
            {{ Form::hidden('upload_agreement',null, ['required','id'=>'f_UploadAgreement','class'=>'image_files upload_agreement_files','title'=>'Please upload agreement']) }}
         </div>
      </div>
   </div>
   @endif
</div>