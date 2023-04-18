<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="allRoomImagesDiv">
    <h4 class="font18 black medium mb-2"> All room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="allRoomfiles" data-name="all_room_images">
                <div class="uploadfileBtn">
                    @if(auth()->user()->hasRole('admin'))
                    <input type="file" id="all_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="all_room_images" data-refimagedivid="allRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @else
                    <input type="file" id="all_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="all_room_images" data-refimagedivid="allRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @endif
                    Room Image
                </div>
            </div>

            @if($formData->propertyAllRoomImages)
            @php $all_room_count = count($formData->propertyAllRoomImages) @endphp
            @foreach($formData->propertyAllRoomImages as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                <input type="hidden" value="{{$rmList->room_image}}" name="all_room_images[]">
                <span class="remove removerecord" data-remove="all_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('all_room_count',@$all_room_count, ['required','id'=>'f_all_room_images','class'=>'allRoomfiles','title'=>'Please upload all room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>