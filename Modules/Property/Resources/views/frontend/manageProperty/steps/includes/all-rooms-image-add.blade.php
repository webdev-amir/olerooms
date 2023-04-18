<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="allRoomImagesDiv">
    <h4 class="font18 black medium mb-2"> Room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="allRoomfiles" data-name="all_room_images">
                <div class="uploadfileBtn">
                    <input type="file" id="all_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" data-fieldname="all_room_images" data-refimagedivid="allRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    Room Image
                </div>
            </div>
            @if($formData && isset($formData->all_room_images))
            @foreach($formData->all_room_images as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$sessionAllData->RoomImagesPath.$rmList}}" title="{{$rmList}}" /><br />
                <input type="hidden" value="{{$rmList}}" name="all_room_images[]">
                <span class="remove removerecord" data-remove="all_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('all_room_count',null, ['required','id'=>'f_all_room_images','class'=>'allRoomfiles Roomfiles','title'=>'Please upload all room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>