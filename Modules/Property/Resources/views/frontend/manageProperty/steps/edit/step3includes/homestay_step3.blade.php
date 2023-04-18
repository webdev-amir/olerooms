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
            @include('property::frontend.manageProperty.steps.includes.edit-all-room-images')
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