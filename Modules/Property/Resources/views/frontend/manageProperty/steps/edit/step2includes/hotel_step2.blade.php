@if(auth()->user()->hasRole('admin'))
{!! Form::model($formData, ['method' => 'PUT','route' => ['admin.manageProperty.update',$formData->id],'id'=>'F_AddSecondSteps']) !!}
@else
{!! Form::model($formData, ['method' => 'PUT','route' => ['manageProperty.update',$formData->id],'id'=>'F_AddSecondSteps']) !!}
@endif

{{ Form::hidden('user_id',Auth::user()->id, []) }}
{{ Form::hidden('step',2, ['id'=>'steps']) }}
{{ Form::hidden('property_type_id',$formData->propertyType->id) }}

<nav aria-label="breadcrumb" class="mB40">
    <ol class="breadcrumb  bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Property</li>
        @if(@$formData->propertyType)
        <li class="breadcrumb-item active" aria-current="page">{{ucfirst($formData->propertyType->name)}}</li>
        @endif
    </ol>
</nav>
<div class="btnrow_progress ">
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_1",'dataTitleContent'=>"Basic Information"])
        @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddSecondSteps"])
    </div>
</div>
<div class="selectproperty_row fadeInUp animated3 delay1 selected">
    <div class="detail_panel mt-3">
        <h4 class="font18 black medium mb-3">Property Inventory</h4>
        <input type="hidden" name="property_type" value="guest-hotel">

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="form-group ermsg">
                    <label>Total Seats(Rooms)</label>
                    {{ Form::text('total_seats',null, ['required','class'=>'form-control isinteger','id'=>'total_seats','data-msg-required'=>'Please enter total seats','placeholder'=>"Total Seats",'autocomplete'=>'off','maxlength'=>4]) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="form-group ermsg">
                    <label>Available Seats(Rooms)</label>
                    {{ Form::text('rented_seats',null, ['required','class'=>'form-control isinteger','id'=>'rented_seats','data-msg-required'=>'Please enter available seats','placeholder'=>"Available Seats",'autocomplete'=>'off','maxlength'=>4]) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="form-group ermsg">
                    <label>Total Floors</label>
                    {{ Form::text('total_floors',null, ['required','class'=>'form-control isinteger','id'=>'total_floors','data-msg-required'=>'Please enter total floor','placeholder'=>"Total Floors",'autocomplete'=>'off','maxlength'=>2]) }}
                </div>
            </div>
        </div>
        <h4 class="font18 black medium mb-2 mt-3">Property Information</h4>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Property Name</label>
                    {{ Form::text('property_name',null, ['required','class'=>'form-control','id'=>'property_name','data-msg-required'=>'Please enter property name','placeholder'=>"Ex. Blue Beds Hotel",'autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Property Available For</label>
                    <div class="row mt-2 newProperty_for">
                        @include('property::frontend.manageProperty.steps.edit.step2includes.property_available_for')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @include('property::frontend.manageProperty.steps.includes.property_desc_step2')

        </div>
        <div class="accordion roomAccordian" id="accordionExample">
            @include('property::frontend.manageProperty.steps.edit.step2includes.edit_property_room_type', ['incCofigRoomTypeArray'=> config('custom.hotel_room_types'),'amountTenure'=>'day'])
        </div>
        @include('property::frontend.manageProperty.steps.edit.step2includes.edit_selfy_aggrement')
    </div>
</div>
<div class="btnrow_progress pb-0">
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_1",'dataTitleContent'=>"Basic Information"])
        @include('property::frontend.manageProperty.steps.includes.continue-button-main',['dataStepNo'=>"2", 'dataIdSubmit'=>"AddSecondSteps", 'dataLoaderContent'=>"Saving Property Informations", 'dataTitleContent'=>"Property Images"])
    </div>
</div>
{!! Form::close() !!}