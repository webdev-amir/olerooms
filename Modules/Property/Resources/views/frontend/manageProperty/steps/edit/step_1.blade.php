<div class="rightPanel_inner steps" id="step_1">
   <nav aria-label="breadcrumb" class="mb-5">
      <ol class="breadcrumb  bg-transparent p-0">
         <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
         <li class="breadcrumb-item active" aria-current="page">Update Property</li>
      </ol>
   </nav>
   <div class="btnrow_progress">
      <div class="d-flex justify-content-between">
         <div></div>
         @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddPropertyDetails", 'dataContentTitle'=>"Property Details"])
      </div>
   </div>
   @if(auth()->user()->hasRole('admin'))
   {!! Form::model($formData, ['method' => 'PUT','route' => ['admin.manageProperty.update',$formData->id],'id'=>'F_AddPropertyDetails']) !!}
   @else
   {!! Form::model($formData, ['method' => 'PUT','route' => ['manageProperty.update',$formData->id],'id'=>'F_AddPropertyDetails']) !!}
   @endif
   {{ Form::hidden('user_id',@$formData->user_id, []) }}
   {{ Form::hidden('step',1, ['id'=>'steps']) }}
   {{ Form::hidden('city_id',@$formData->city_id, ['id'=>'city_id']) }}
   {{ Form::hidden('area_id',@$formData->area_id, ['id'=>'area_id']) }}
   {{ Form::hidden('gky',setting_item('map_gmap_key'), ['id'=>'gky']) }}
   <div class="selectproperty_row fadeInUp animated3 delay1 selected">
      <h4> Property Type </h4>
      <div class="ermsg">
         <div class="selectProperty mt-4">
            <div class="form-check p-0">
               {!! Form::radio('property_type',$formData->propertyType->slug, $formData->propertyType->slug, ['required','class' => 'form-check-input','id'=>"property_type_$formData->propertyType->slug"]) !!}
               <label class=" form-check-label" for="property_type_{{$formData->propertyType->slug}}">
                  <span class="propertyIcon"><img src="{{$formData->propertyType->PicturePath}}" alt="{{$formData->propertyType->name}}" title="{{$formData->propertyType->name}}" /></span>
                  <span class="propertyText"> {{$formData->propertyType->name}} </span>
               </label>
            </div>
            {{ Form::hidden('property_type_id',$formData->property_type_id, ['required','title'=>'Property type is required']) }}

         </div>
      </div>
      <div class="detail_panel mt-3">
         <h4 class="font18 black medium mb-3">Add Location & address</h4>
         <div class="form-group ermsg">
            <label>Enter Your Full Address</label>
            {{ Form::text('full_address',$formData->full_address, ['required','class'=>'form-control','id'=>'address','data-msg-required'=>'Please enter full address','placeholder'=>"Where is your property located?",'autocomplete'=>'off']) }}
         </div>
         <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 ">
               <div class="form-group selecticon ermsg">
                  <i class="ri-arrow-down-s-line"></i>
                  <label>Select State</label>
                  {{ Form::select('state_id', [''=>'Select State']+$stateLists, $formData->state_id , ['required','class' => 'form-control','title'=>'Please select state','id'=>'state-dropdown']) }}
               </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
               <div class="form-group selecticon ermsg">
                  <i class="ri-arrow-down-s-line"></i>
                  <label>Select City</label>
                  <select name="city" class="form-control" id="city-dropdown" required title="Please select city">
                     <option value="">Select City</option>
                  </select>
               </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
               <div class="form-group selecticon ermsg">
                  <i class="ri-arrow-down-s-line"></i>
                  <label>Enter Area</label>
                  <select name="area" class="form-control" id="area-dropdown" required title="Please select area">
                     <option value="">Select Area</option>
                  </select>
               </div>
            </div>
         </div>
         <div class="form-group selecticon ermsg" id="searchtag">
            <i class="ri-record-circle-line near-me" id="locator-button">Current Location</i>
            <label>Enter Location</label>
            {{ Form::text('map_location',$formData->map_location, ['required','class'=>'parent_text form-control','id'=>'location','data-msg-required'=>'Please enter location','placeholder'=>"Enter Location",'autocomplete'=>'off']) }}
            {{ Form::hidden('lat',NULL, ['id'=>'cityLat']) }}
            {{ Form::hidden('long',NULL, ['id'=>'cityLng']) }}
         </div>
         <div class="form-group">
            <div class="mapSec">
               <div id="embedMap" class="w-100" style="width: 400px; height: 300px;">
                  <!--Google map will be embedded here-->
                  <img class="w-100" src="{{URL::to('images/map.png')}}" alt="Map Location" />
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="btnrow_progress pb-0">
      <div class="progress">
         <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="d-flex justify-content-between">
         <div></div>
         @include('property::frontend.manageProperty.steps.includes.continue-button-main',['dataStepNo'=>"1", 'dataIdSubmit'=>"AddPropertyDetails", 'dataLoaderContent'=>"Saving Property Details", 'dataTitleContent'=>"Property Details"])
      </div>
   </div>
   {!! Form::close() !!}
</div>