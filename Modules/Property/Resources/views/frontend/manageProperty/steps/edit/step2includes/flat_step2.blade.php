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
<div class="btnrow_progress">
    <div class="d-flex justify-content-between">
        @include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_1",'dataTitleContent'=>"Basic Information"])
        @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddSecondSteps"])
    </div>
</div>
<div class="selectproperty_row fadeInUp animated3 delay1 selected">
    <div class="detail_panel mt-3">
        <h4 class="font18 black medium mb-2 mt-3">Property Information</h4>
        <input type="hidden" name="room_type[]" value="all">
        <input type="hidden" name="property_type" value="flat">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Property Name</label>
                    {{ Form::text('property_name',null, ['required','class'=>'form-control','id'=>'property_name','data-msg-required'=>'Please enter property name','placeholder'=>"Ex. Blue Beds Flat",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Amount Per Month</label>
                    {{ Form::text('amount',null, ['required','class'=>'form-control isinteger','id'=>'amount','data-msg-required'=>'Please enter amount per month','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Amount Per Month",'autocomplete'=>'off']) }}
                    <p class="image-hint">{{@$formData->propertyType->AdminCommissionText}}</p>
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
                    <div class="row mt-2 newProperty_for">
                        @include('property::frontend.manageProperty.steps.edit.step2includes.property_available_for')
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group selecticon ermsg">
                    <i class="ri-arrow-down-s-line"></i>
                    <label>BHK</label>
                    {{ Form::select('bhk_type', [''=>'BHK']+config::get('custom.bhk_type'), NULL , ['required','class' => 'form-control','title'=>'Please select BHK type','id'=>'bhk_type']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Security Deposit</label>
                    {{ Form::text('security_deposit_amount',null, ['required','class'=>'form-control isinteger','id'=>'security_deposit_amount','data-msg-required'=>'Please enter security deposit amount','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Security Deposit Amount",'autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Flat No.</label>
                    {{ Form::text('flat_no',null, ['required','class'=>'form-control','id'=>'flat_no','data-msg-required'=>'Please enter flat no.','maxlength'=>'12','data-msg-maxlength'=>'','placeholder'=>"Flat No. ",'autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Floor No.</label>
                    {{ Form::text('floor_no',null, ['required','class'=>'form-control','id'=>'floor_no','data-msg-required'=>'Please enter floor no.','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Floor No. ",'autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Convenient Time To Visit Property</label>
                    {{ Form::text('convenient_time',null, ['required','class'=>'form-control','id'=>'convenient_time','data-msg-required'=>'Please enter convenient time to visit property','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Convenient Time To Visit Property",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Carpet Area(in Sq. Fts.)</label>
                    {{ Form::text('carpet_area',null, ['required','class'=>'form-control isinteger','id'=>'carpet_area','data-msg-required'=>'Please enter carpet area (in sq)','maxlength'=>'12','data-msg-maxlength'=>'','pattern'=>"\d*",'placeholder'=>"Carpet Area (in Sq)",'autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Kitchen Modular</label>
                    <div class="row mt-2">
                        <div class="col col-lg-2">
                            <div class="d-flex align-items-center formCheck">
                                {{ Form::radio('kitchen_modular', 'yes', null , ['required','id'=>"kitchen_modular1",'title'=>'Please select kitchen modular', 'value'=>'yes']) }}
                                <label for="kitchen_modular1" class="mb0 remembertext d-flex align-items-center">
                                    <span class="checkmark fcheckbox">Yes</span>
                                </label>
                            </div>
                        </div>

                        <div class="col col-lg-2">
                            <div class="d-flex align-items-center formCheck">
                                {{ Form::radio('kitchen_modular', 'no', null , ['required','id'=>"kitchen_modular2",'title'=>'Please select kitchen modular', 'value'=>'no']) }}
                                <label for="kitchen_modular2" class="mb0 remembertext d-flex align-items-center">
                                    <span class="checkmark fcheckbox">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group ermsg">
                    <label>Parking Space Available</label>
                    <div class="row mt-2">
                        <div class="col col-lg-2">
                            <div class="d-flex align-items-center formCheck">
                                {{ Form::radio('parking_space_avail', 'yes', null , ['required','id'=>"parking_space_avail1",'title'=>'Please select parking space', 'value'=>'yes']) }}
                                <label for="parking_space_avail1" class="mb0 remembertext d-flex align-items-center">
                                    <span class="checkmark fcheckbox">Yes</span>
                                </label>
                            </div>
                        </div>

                        <div class="col col-lg-2">
                            <div class="d-flex align-items-center formCheck">
                                {{ Form::radio('parking_space_avail', 'no', null , ['required','id'=>"parking_space_avail2",'title'=>'Please select parking space', 'value'=>'no']) }}
                                <label for="parking_space_avail2" class="mb0 remembertext d-flex align-items-center">
                                    <span class="checkmark fcheckbox">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('property::frontend.manageProperty.steps.includes.property_desc_step2')

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