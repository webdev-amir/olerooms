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
        <li class="breadcrumb-item active" aria-current="page" id="propertyType">Homestay</li>
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
        <h4 class="font18 black medium mb-2 mt-3">Property Information</h4>
        <div class="row">
            <input type="hidden" name="room_type[]" value="all">
            <input type="hidden" name="property_type" value="homestay">

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Property Name</label>
                    {{ Form::text('property_name',null, ['required','class'=>'form-control','id'=>'property_name','data-msg-required'=>'Please enter property name','placeholder'=>"Ex. Blue Beds Homestay",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Amount Per Day</label>
                    {{ Form::text('amount',null, ['required','class'=>'form-control isinteger','id'=>'amount','data-msg-required'=>'Please enter amount per day','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Amount Per Day",'autocomplete'=>'off']) }}
                    <p class="image-hint">{{!empty($propertyTypeData) ?$propertyTypeData->AdminCommissionText:''}}</p>

                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group selecticon ermsg">
                    <i class="ri-arrow-down-s-line"></i>
                    <label>Property Furnished Type</label>
                    {{ Form::select('furnished_type', [''=>'Property Furnished Type']+config::get('custom.furniture_filter'), NULL , ['required','class' => 'form-control','title'=>'Please select available type','id'=>'furnished_type']) }}
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
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group selecticon ermsg">
                    <i class="ri-arrow-down-s-line"></i>
                    <label>Type of Homestay</label>
                    {{ Form::select('homestay_type', [''=>'Type of Homestay']+config::get('custom.homestay_type'), NULL , ['required','class' => 'form-control','title'=>'Please select homestay type','id'=>'homestay_type']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>No. of Rooms</label>
                    {{ Form::text('rooms',null, ['required','class'=>'form-control isinteger','id'=>'rooms','data-msg-required'=>'Please enter no. of rooms','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"No. of Rooms",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>No. of Beds</label>
                    {{ Form::text('beds',null, ['required','class'=>'form-control isinteger','id'=>'beds','data-msg-required'=>'Please enter no. of beds','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"No. of Beds",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Capacity of Guests</label>
                    {{ Form::text('guest_capacity',null, ['required','class'=>'form-control isinteger','id'=>'guest_capacity','data-msg-required'=>'Please enter no. of guest capacity','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Capacity of Guests",'autocomplete'=>'off']) }}
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

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="radiobuttonRow d-flex align-items-center h-100">
                    <div class="d-flex align-items-center">
                        <div class="form-check p-0 mr-4">
                            <input class="mr-2" type="radio" name="is_homestay_ac" id="upi1" value="1">
                            <label for="upi1" class="mb0 font18 medium"> AC </label>
                        </div>
                        <div class="form-check p-0 mr-4">
                            <input class="mr-2" type="radio" name="is_homestay_ac" checked id="upi" value="0">
                            <label for="upi" class="mb0 font18 medium"> Non AC </label>
                        </div>
                    </div>
                </div>
            </div>
            @include('property::frontend.manageProperty.steps.includes.property_desc_step2')

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