@if(auth()->user()->hasRole('admin'))
{!! Form::model($formData, ['method' => 'PUT','route' => ['admin.manageProperty.update',$formData->id],'id'=>'F_AddThirdSteps']) !!}
@else
{!! Form::model($formData, ['method' => 'PUT','route' => ['manageProperty.update',$formData->id],'id'=>'F_AddThirdSteps']) !!}
@endif

{{ Form::hidden('user_id',Auth::user()->id, []) }}
{{ Form::hidden('step',3, ['id'=>'steps']) }}
<nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb  bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item">Update Property</li>
        @if(@$formData->propertyType)
        <li class="breadcrumb-item active" aria-current="page">{{ucfirst($formData->propertyType->name)}}</li>
        @endif
    </ol>
</nav>
<div class="btnrow_progress">
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_2",'dataTitleContent'=>"Property Images"])
        @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddThirdSteps"])
    </div>
</div>
<div class="selectproperty_row fadeInUp animated3 delay1 selected">
    @include('property::frontend.manageProperty.steps.includes.edit-amenities')
    <div class="detail_panel mt-1">
        <div class="row">
            @include('property::frontend.manageProperty.steps.includes.edit-cover-img')
            @php
            $required_standard = in_array('standard',(array)$formData->room_type)?'required' :'';
            @endphp
            <div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="standardRoomImagesDiv" style="display:{{$required_standard!=''?'':'none'}}">
                <h4 class="font18 black medium mb-2">Standard room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
                <div class="form-group ermsg">
                    <div class="uploadImages_block">
                        <div class="uploadMultiplefiles" id="standardRoomfiles" data-name="standard_room_images">
                            <div class="uploadfileBtn">



                                @if(auth()->user()->hasRole('admin'))
                                <input type="file" id="standard_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="standard_room_images" data-refimagedivid="standardRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @else
                                <input type="file" id="standard_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="standard_room_images" data-refimagedivid="standardRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @endif


                                Room Image
                            </div>
                        </div>
                        @if($formData->propertyStandardRoomType && $formData->propertyStandardRoomImages)
                        @php $standard_room_count = count($formData->propertyStandardRoomImages) @endphp
                        @foreach($formData->propertyStandardRoomImages as $key => $rmList)
                        <span class="pip">
                            <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                            <input type="hidden" value="{{$rmList->room_image}}" name="standard_room_images[]">
                            <span class="remove removerecord" data-remove="standard_room_images">Remove image</span>
                        </span>
                        @endforeach
                        @endif
                    </div>
                    {{ Form::hidden('standard_room_count',@$standard_room_count, [$required_standard,'id'=>'f_standard_room_images','class'=>'standardRoomfiles','title'=>'Please upload standard room image','min'=>1]) }}
                    <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
                </div>
            </div>


            @php
            $required_deluxe = in_array('deluxe',(array)$formData->room_type)?'required' :'';
            @endphp
            <div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="deluxeRoomImagesDiv" style="display:{{$required_deluxe!=''?'':'none'}}">
                <h4 class="font18 black medium mb-2"> Deluxe room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
                <div class="form-group ermsg">
                    <div class="uploadImages_block">
                        <div class="uploadMultiplefiles" id="deluxeRoomfiles" data-name="deluxe_room_images">
                            <div class="uploadfileBtn">

                                @if(auth()->user()->hasRole('admin'))
                                <input type="file" id="deluxe_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="deluxe_room_images" data-refimagedivid="deluxeRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @else
                                <input type="file" id="deluxe_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="deluxe_room_images" data-refimagedivid="deluxeRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @endif

                                Room Image
                            </div>
                        </div>

                        @if($formData->propertyDeluxeRoomType && $formData->propertyDeluxeRoomImages)
                        @php $deluxe_room_count = count($formData->propertyDeluxeRoomImages) @endphp
                        @foreach($formData->propertyDeluxeRoomImages as $key => $rmList)
                        <span class="pip">
                            <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                            <input type="hidden" value="{{$rmList->room_image}}" name="deluxe_room_images[]">
                            <span class="remove removerecord" data-remove="deluxe_room_images">Remove image</span>
                        </span>
                        @endforeach
                        @endif
                    </div>
                    {{ Form::hidden('deluxe_room_count',@$deluxe_room_count, [$required_deluxe,'id'=>'f_deluxe_room_images','class'=>'deluxeRoomfiles','title'=>'Please upload deluxe room image','min'=>1]) }}
                    <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
                </div>
            </div>

            @php
            $required_suite = in_array('suite',(array)$formData->room_type)?'required' :'';
            @endphp
            <div class="col-sm-12 col-md-6 col-lg-6 RoomImagesDiv" id="suiteRoomImagesDiv" style="display:{{$required_suite!=''?'':'none'}}">
                <h4 class="font18 black medium mb-2"> Suite room images <span class="grey font14 regular">( Upto 5 Images )</span></h4>
                <div class="form-group ermsg">
                    <div class="uploadImages_block">
                        <div class="uploadMultiplefiles" id="suiteRoomfiles" data-name="suite_room_images">
                            <div class="uploadfileBtn">

                                @if(auth()->user()->hasRole('admin'))
                                <input type="file" id="suite_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('admin.manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="suite_room_images" data-refimagedivid="suiteRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @else
                                <input type="file" id="suite_room_images" class="onlyimageuploadmultiple" data-uploadurl="{{route('manageProperty.uploadRoomImages',[$formData->user_id])}}" data-fieldname="suite_room_images" data-refimagedivid="suiteRoomfiles" data-maxfile=5 />
                                <i class="ri-upload-line d-block"></i>
                                @endif


                                <i class="ri-upload-line d-block"></i>
                                Room Image
                            </div>
                        </div>

                        @if($formData->propertySuiteRoomType && $formData->propertySuiteRoomImages)
                        @php $suite_room_count = count($formData->propertySuiteRoomImages) @endphp
                        @foreach($formData->propertySuiteRoomImages as $key => $rmList)
                        <span class="pip">
                            <img class="imageThumb" src="{{$rmList->RoomImageThunbnail}}" title="{{$rmList->room_name}}" /><br />
                            <input type="hidden" value="{{$rmList->room_image}}" name="suite_room_images[]">
                            <span class="remove removerecord" data-remove="suite_room_images">Remove image</span>
                        </span>
                        @endforeach
                        @endif
                    </div>
                    {{ Form::hidden('suite_room_count',@$suite_room_count, [$required_suite,'id'=>'f_suite_room_images','class'=>'suiteRoomfiles','title'=>'Please upload suite room image','min'=>1]) }}
                    <p class="image-hint">{{trans('flash.hints.image-size-hints')}}</p>
                </div>
            </div>
            @include('property::frontend.manageProperty.steps.includes.video-edit')
            @include('property::frontend.manageProperty.steps.includes.note-section-room-image')

        </div>
    </div>
</div>
<div class="btnrow_progress pb-0">
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width:60%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_2",'dataTitleContent'=>"Property Images"])
        @include('property::frontend.manageProperty.steps.includes.continue-button-main',['dataStepNo'=>"3", 'dataIdSubmit'=>"AddThirdSteps", 'dataLoaderContent'=>"Saving Property Amenities and Images", 'dataTitleContent'=>"Payment Details"])
    </div>
</div>
{!! Form::close() !!}