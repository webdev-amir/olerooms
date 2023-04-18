<div class="col-sm-12 col-md-6 col-lg-6">
    <h4 class="font18 black medium mb-2"> Property Cover Image </h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadSinglefile" id="cover_image_files">
                <div class="uploadfileBtn">
                    <input type="file" id="cover_image" class="onlyimageupload" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" />
                    <i class="ri-upload-line d-block"></i>
                    Cover Image
                </div>
            </div>
            @if($formData && @$sessionAllData->CoverImagePath)
            <span class="pip"><img class="imageThumb" src="{{@$sessionAllData->CoverImagePath}}" title="Cover Image" /><br />
                <span class="remove removesingle" data-remove="cover_image_files">Remove image</span></span>
            @endif
        </div>
        {{ Form::hidden('cover_image',null, ['required','id'=>'f_cover_image','class'=>'cover_image_files','title'=>'Please upload Cover Image']) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>