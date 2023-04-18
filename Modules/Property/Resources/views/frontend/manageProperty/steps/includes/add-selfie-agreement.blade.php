<h4 class="font18 black medium mb-2 mT30">Electricity bill image</h4>
<div class="row">
    <div class="col-mm-6">
        <div class="attactmentFile ermsg">
            <input type="file" id="EBill" name="EBill" class="form-control imageandpdfupload" data-uploadurl="{{route('property.mediaStore' ,[auth()->user()->id])}}">
            {{ Form::hidden('electricity_bill',null, ['id'=>'f_EBill','title'=>'Please upload electricity bill']) }}
        </div>
        @if(@$formData->electricity_bill)
        <br>
        <strong>Filename: {{$formData->electricity_bill}}</strong>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="uploadrow ermsg">
            <h4 class="font18 black medium mb-2 mT30"> Upload Selfie Image</h4>
            <div class="uploadImages_block ">
                <div class="uploadSinglefile" id="selfyfile">
                    <div class="uploadfileBtn">
                        <input type="file" id="upload_selfie" class="onlyimageupload" data-uploadurl="{{route('property.mediaStore' ,[auth()->user()->id])}}" />
                        <i class="ri-upload-line"></i>
                        Upload Selfie
                    </div>
                </div>
                @if($formData && @$sessionAllData->SelfieImagePath)
                <span class="pip"><img class="imageThumb" src="{{@$sessionAllData->SelfieImagePath}}" title="Cover Image" /><br />
                    <span class="remove removesingle" data-remove="upload_selfie_files">Remove image</span></span>
                @endif
                {{ Form::hidden('upload_selfie',null, ['required','id'=>'f_upload_selfie','class'=>'selfyfile upload_selfie_files','title'=>'Please upload selfie image']) }}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="uploadrow ermsg">
            <h4 class="font18 black medium mb-2 mT30"> Upload Agreement</h4>
            <div class="uploadImages_block ">
                <div class="uploadSinglefile" id="image_files">
                    <div class="uploadfileBtn">
                        <input type="file" id="UploadAgreement" class="imageandpdfupload" data-uploadurl="{{route('manageProperty.uploadAgreement',[auth()->user()->id])}}" name="UploadAgreement" />
                        <i class="ri-upload-line"></i>
                        Upload Agreement
                    </div>
                </div>
                <div id="ht_UploadAgreement"></div>
                @if($formData)
                @if (preg_match('/(\.jpg|\.png|\.bmp)$/i', @$formData->upload_agreement))
                <span class="pip"><img class="imageThumb" src="{{@$sessionAllData->AgreementPath}}" title="Cover Image" /><br />
                    <span class="remove removesingle" data-remove="upload_agreement_files">Remove image</span></span>
                @else
                <strong>Filename: {{$formData->upload_agreement}}</strong>
                @endif
                @endif
                {{ Form::hidden('upload_agreement',null, ['required','id'=>'f_UploadAgreement','class'=>'image_files upload_agreement_files','title'=>'Please upload agreement']) }}
            </div>
        </div>
    </div>
</div>