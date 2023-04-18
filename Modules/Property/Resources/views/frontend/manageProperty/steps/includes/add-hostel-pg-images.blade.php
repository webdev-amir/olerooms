@php
$required_single = !empty($sessionData->step_2) && in_array('single',(array)$sessionData->step_2->room_type)?'required' :'';
@endphp

<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="singleRoomImagesDiv" style="display:{{$required_single!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Single room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="singleRoomfiles" data-name="single_room_images">
                <div class="uploadfileBtn">
                    <input type="file" id="single_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" data-fieldname="single_room_images" data-refimagedivid="singleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    Room Image
                </div>
            </div>
            @if($formData && isset($formData->single_room_images))
            @foreach($formData->single_room_images as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$sessionAllData->RoomImagesPath.$rmList}}" title="{{$rmList}}" /><br />
                <input type="hidden" value="{{$rmList}}" name="single_room_images[]">
                <span class="remove removerecord" data-remove="single_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('single_room_count',null, [$required_single,'id'=>'f_single_room_images','class'=>'singleRoomfiles Roomfiles','title'=>'Please upload single room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>
@php
$required_double = !empty($sessionData->step_2) && in_array('double',(array)$sessionData->step_2->room_type)?'required' :'';
@endphp

<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="doubleRoomImagesDiv" style="display:{{$required_double!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Double room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="doubleRoomfiles" data-name="double_room_images">
                <div class="uploadfileBtn">
                    <input type="file" id="double_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" data-fieldname="double_room_images" data-refimagedivid="doubleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    Room Image
                </div>
            </div>
            @if($formData && isset($formData->double_room_images))
            @foreach($formData->double_room_images as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$sessionAllData->RoomImagesPath.$rmList}}" title="{{$rmList}}" /><br />
                <input type="hidden" value="{{$rmList}}" name="double_room_images[]">
                <span class="remove removerecord" data-remove="double_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('double_room_count',null, [$required_double, 'id'=>'f_double_room_images','class'=>'doubleRoomfiles Roomfiles','title'=>'Please upload double room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>

@php
$required_triple = !empty($sessionData->step_2) && in_array('triple',(array)$sessionData->step_2->room_type)?'required' :'';
@endphp

<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="tripleRoomImagesDiv" style="display:{{$required_triple!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Triple room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="tripleRoomfiles" data-name="triple_room_images">
                <div class="uploadfileBtn">
                    <input type="file" id="triple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" data-fieldname="triple_room_images" data-refimagedivid="tripleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    Room Image
                </div>
            </div>
            @if($formData && isset($formData->triple_room_images))
            @foreach($formData->triple_room_images as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$sessionAllData->RoomImagesPath.$rmList}}" title="{{$rmList}}" /><br />
                <input type="hidden" value="{{$rmList}}" name="triple_room_images[]">
                <span class="remove removerecord" data-remove="triple_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('triple_room_count',null, [$required_triple, 'id'=>'f_triple_room_images','class'=>'tripleRoomfiles Roomfiles','title'=>'Please upload triple room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>

@php
$required_quadruple = !empty($sessionData->step_2) && in_array('quadruple',(array)$sessionData->step_2->room_type)?'required' :'';
@endphp

<div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="quadrupleRoomImagesDiv" style="display:{{$required_quadruple!=''?'':'none'}}">
    <h4 class="font18 black medium mb-2"> Quadruple room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
    <div class="form-group ermsg">
        <div class="uploadImages_block">
            <div class="uploadMultiplefiles" id="quadrupleRoomfiles" data-name="quadruple_room_images">
                <div class="uploadfileBtn">
                    <input type="file" id="quadruple_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[auth()->user()->id])}}" data-fieldname="quadruple_room_images" data-refimagedivid="quadrupleRoomfiles" data-maxfile=5 />
                    <i class="ri-upload-line d-block"></i>
                    Room Image
                </div>
            </div>
            @if($formData && isset($formData->quadruple_room_images))
            @foreach($formData->quadruple_room_images as $key => $rmList)
            <span class="pip">
                <img class="imageThumb" src="{{$sessionAllData->RoomImagesPath.$rmList}}" title="{{$rmList}}" /><br />
                <input type="hidden" value="{{$rmList}}" name="quadruple_room_images[]">
                <span class="remove removerecord" data-remove="quadruple_room_images">Remove image</span>
            </span>
            @endforeach
            @endif
        </div>
        {{ Form::hidden('quadruple_room_count',null, [ $required_quadruple ,'id'=>'f_quadruple_room_images','class'=>'quadrupleRoomfiles Roomfiles','title'=>'Please upload quadruple room image','min'=>1]) }}
        <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
    </div>
</div>