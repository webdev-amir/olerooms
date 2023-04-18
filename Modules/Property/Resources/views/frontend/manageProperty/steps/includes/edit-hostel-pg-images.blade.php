@php
$required_single = in_array('single',(array)$formData->room_type)?'required' :'';
@endphp
<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="singleRoomImagesDiv" style="display:{{$required_single!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Single room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="singleRoomfiles" data-name="single_room_images">
                <div class="uploadfileBtn">

                    @if(auth()->user()->hasRole('admin'))
                    <input type="file" id="single_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="single_room_images" data-refimagedivid="singleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @else
                    <input type="file" id="single_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="single_room_images" data-refimagedivid="singleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @endif

                    Room Image
                </div>
            </div>

            @if($formData->propertySingleRoomType && $formData->propertySingleRoomImages)
            @php $single_room_count = count($formData->propertySingleRoomImages) @endphp
            @foreach($formData->propertySingleRoomImages as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                <input type="hidden" value="{{$rmList->room_image}}" name="single_room_images[]">
                <span class="remove removerecord" data-remove="single_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('single_room_count',@$single_room_count, [$required_single,'id'=>'f_single_room_images','class'=>'singleRoomfiles','title'=>'Please upload single room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>
@php
$required_double = in_array('double',(array)$formData->room_type)?'required' :'';
@endphp
<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="doubleRoomImagesDiv" style="display:{{$required_double!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Double room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="doubleRoomfiles" data-name="double_room_images">
                <div class="uploadfileBtn">
                    @if(auth()->user()->hasRole('admin'))

                    <input type="file" id="double_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="double_room_images" data-refimagedivid="doubleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @else

                    <input type="file" id="double_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="double_room_images" data-refimagedivid="doubleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @endif

                    Room Image
                </div>
            </div>
            @if($formData->propertyDoubleRoomType && $formData->propertyDoubleRoomImages)
            @php $double_room_count = count($formData->propertyDoubleRoomImages) @endphp
            @foreach($formData->propertyDoubleRoomImages as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                <input type="hidden" value="{{$rmList->room_image}}" name="double_room_images[]">
                <span class="remove removerecord" data-remove="double_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('double_room_count',@$double_room_count, [$required_double,'id'=>'f_double_room_images','class'=>'singleRoomfiles','title'=>'Please upload double room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>

@php
$required_triple = in_array('triple',(array)$formData->room_type)?'required' :'';
@endphp
<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="tripleRoomImagesDiv" style="display:{{$required_triple!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Triple room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="tripleRoomfiles" data-name="triple_room_images">
                <div class="uploadfileBtn">
                    @if(auth()->user()->hasRole('admin'))
                    <input type="file" id="triple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="triple_room_images" data-refimagedivid="tripleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @else
                    <input type="file" id="triple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="triple_room_images" data-refimagedivid="tripleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @endif



                    Room Image
                </div>
            </div>
            @if($formData->propertyTripleRoomType && $formData->propertyTripleRoomImages)
            @php $triple_room_count = count($formData->propertyTripleRoomImages) @endphp
            @foreach($formData->propertyTripleRoomImages as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                <input type="hidden" value="{{$rmList->room_image}}" name="triple_room_images[]">
                <span class="remove removerecord" data-remove="triple_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('triple_room_count',@$triple_room_count, [$required_triple,'id'=>'f_triple_room_images','class'=>'tripleRoomfiles','title'=>'Please upload triple room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>
@php
$required_quadruple = in_array('quadruple',(array)$formData->room_type)?'required' :'';
@endphp
<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="quadrupleRoomImagesDiv" style="display:{{$required_quadruple!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Quadruple room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="quadrupleRoomfiles" data-name="quadruple_room_images">
                <div class="uploadfileBtn">

                    @if(auth()->user()->hasRole('admin'))
                    <input type="file" id="quadruple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="quadruple_room_images" data-refimagedivid="quadrupleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @else
                    <input type="file" id="quadruple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="quadruple_room_images" data-refimagedivid="quadrupleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    @endif



                    Room Image
                </div>
            </div>
            @if($formData->propertyQuadrupleRoomType && $formData->propertyQuadrupleRoomImages)
            @php $quadruple_room_count = count($formData->propertyQuadrupleRoomImages) @endphp
            @foreach($formData->propertyQuadrupleRoomImages as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                <input type="hidden" value="{{$rmList->room_image}}" name="quadruple_room_images[]">
                <span class="remove removerecord" data-remove="quadruple_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('quadruple_room_count',@$quadruple_room_count, [$required_quadruple,'id'=>'f_quadruple_room_images','class'=>'quadrupleRoomfiles','title'=>'Please upload quadruple room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>