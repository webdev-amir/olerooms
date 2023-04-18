@php
$formData = [];
if($sessionData && isset($sessionData->step_3)){
$formData = $sessionData->step_3;
}
@endphp
{!! Form::model($formData, ['method' => 'post','route' => ['manageProperty.storeProperty'],'class'=>'','id'=>'F_AddThirdSteps']) !!}
{{ Form::hidden('user_id',Auth::user()->id, []) }}
{{ Form::hidden('step',3, ['id'=>'steps']) }}
{{ Form::hidden('session_property_entry',NULL, ['id'=>'session_property_entry']) }}
<nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb  bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Property</li>
        <li class="breadcrumb-item active" aria-current="page">Flat</li>
    </ol>
</nav>
<div class="btnrow_progress ">
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_2",'dataTitleContent'=>"Property Images"])
        @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddThirdSteps"])
    </div>
</div>
<div class="selectproperty_row fadeInUp animated3 delay1 selected">
    @include('property::frontend.manageProperty.steps.includes.add-amenities')
    <div class="detail_panel mt-1">
        <div class="row">
            @include('property::frontend.manageProperty.steps.includes.add-cover-img')
            @include('property::frontend.manageProperty.steps.includes.all-rooms-image-add')
            @include('property::frontend.manageProperty.steps.includes.video-add')
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