@php
$formData = [];
$propertyTypeId = $sessionData && isset($sessionData->step_1) ? $sessionData->step_1->property_type : 0;
if($sessionData && isset($sessionData->step_2)){
$formData = $sessionData->step_2;
}
@endphp

{!! Form::model($formData, ['method' => 'post','route' => ['manageProperty.storeProperty'],'class'=>'','id'=>'F_AddSecondSteps']) !!}
{{ Form::hidden('user_id',Auth::user()->id, []) }}
{{ Form::hidden('step',2, ['id'=>'steps']) }}
{{ Form::hidden('property_type_id',$propertyTypeId) }}

{{ Form::hidden('session_property_entry',NULL, ['id'=>'session_property_entry']) }}
<nav aria-label="breadcrumb" class="mB40">
    <ol class="breadcrumb  bg-transparent p-0">
        <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Property</li>
        <li class="breadcrumb-item active" aria-current="page" id="propertyType">Hostel/PG</li>
    </ol>
</nav>
<div class="btnrow_progress">

    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_1",'dataTitleContent'=>"Basic Information"])
        @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddSecondSteps"])
    </div>
</div>
<div class="selectproperty_row fadeInUp animated3 delay1 selected">

    <div class="detail_panel mt-3">
        <h4 class="font18 black medium mb-3">Property Inventory</h4>
        <input type="hidden" name="property_type" value="hostel-pg">

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="form-group ermsg">
                    <label>Total Seats</label>
                    {{ Form::text('total_seats',null, ['required','class'=>'form-control isinteger','id'=>'total_seats','data-msg-required'=>'Please enter total seats','placeholder'=>"Total Seats",'autocomplete'=>'off','maxlength'=>4]) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="form-group ermsg">
                    <label>Available Seats</label>
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
                    {{ Form::text('property_name',null, ['required','class'=>'form-control','id'=>'property_name','data-msg-required'=>'Please enter property name','placeholder'=>"Ex. Blue Beds Hostel",'autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Property Available For</label>
                    <div class="row newProperty_for">
                        @include('property::frontend.manageProperty.steps.create.step2includes.property_available_for')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Security Deposit</label>
                    {{ Form::text('security_deposit_amount',null, ['required','class'=>'form-control isinteger','id'=>'security_deposit_amount','data-msg-required'=>'Please enter security deposit amount','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Security Deposit Amount",'autocomplete'=>'off']) }}
                </div>
            </div>
            @include('property::frontend.manageProperty.steps.includes.property_desc_step2')

        </div>

        <div class="accordion roomAccordian" id="accordionExample">
            @include('property::frontend.manageProperty.steps.create.step2includes.add_property_room_types', ['incCofigRoomTypeArray'=> config('custom.hostelpg_room_types'),'amountTenure'=>'month'])
        </div>
        @include('property::frontend.manageProperty.steps.includes.add-selfie-agreement',['isRequiredElectricityBill'=>""])


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