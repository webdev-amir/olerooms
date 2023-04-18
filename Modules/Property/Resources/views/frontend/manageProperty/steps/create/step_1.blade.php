<div class="rightPanel_inner steps" id="step_1" @if($sessionAllData && isset($sessionAllData->current_step) && $sessionAllData->current_step != 1) style="display: none;" @endif >
   <nav aria-label="breadcrumb" class="mb-5">
      <ol class="breadcrumb  bg-transparent p-0">
         <li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
         <li class="breadcrumb-item active" aria-current="page">Add Property</li>
      </ol>
   </nav>
   <div class="btnrow_progress">
      <div class="d-flex justify-content-between">
         <div></div>
         @include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddPropertyDetails"])
      </div>
   </div>
   {!! Form::model($formData, ['method' => 'post','route' => ['manageProperty.storeProperty'],'id'=>'F_AddPropertyDetails']) !!}
   {{ Form::hidden('user_id',Auth::user()->id, []) }}
   {{ Form::hidden('step',1, ['id'=>'steps']) }}
   {{ Form::hidden('session_property_entry',NULL, ['id'=>'session_property_entry']) }}
   {{ Form::hidden('city_id',@$formData->city, ['id'=>'city_id']) }}
   {{ Form::hidden('area_id',@$formData->area, ['id'=>'area_id']) }}
   {{ Form::hidden('gky',setting_item('map_gmap_key'), ['id'=>'gky']) }}
   <div class="selectproperty_row fadeInUp animated3 delay1 selected">
      <h4> Select Property Type </h4>
      <div class="ermsg">
         <div class="selectProperty mt-4">
            @if(count($propertyTypes)>0)
            @foreach($propertyTypes as $ptList)
            <div class="form-check p-0">
               {!! Form::radio('property_type',$ptList->slug, null, ['required','class' => 'form-check-input','id'=>"property_type_$ptList->slug"]) !!}
               <label class="form-check-label" for="property_type_{{$ptList->slug}}">

                  <span class="propertyIcon"><img src="{{$ptList->PicturePath}}" alt="{{$ptList->name}}" title="{{$ptList->name}}" /></span>
                  <span class="propertyText"> {{$ptList->name}} </span>
               </label>
            </div>
            @endforeach
            @else
            <div class="form-check p-0 ermsg" style="color: red;">
               {{ Form::hidden('property_type_id',NULL, ['required','title'=>'Property type is required']) }}
               Property Type Not Found please contact to admin<br>
            </div>
            @endif
         </div>
      </div>
      <div class="detail_panel mt-3">
         <h4 class="font18 black medium mb-3">Add Location & address</h4>
         <div class="form-group ermsg">
            <label>Enter Your Full Address</label>
            {{ Form::text('address',null, ['required','class'=>'form-control','id'=>'address','data-msg-required'=>'Please enter full address','placeholder'=>"Where is your property located?",'autocomplete'=>'off']) }}
         </div>
         <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 ">
               <div class="form-group selecticon ermsg">
                  <i class="ri-arrow-down-s-line"></i>
                  <label>Select State</label>
                  {{ Form::select('state', [''=>'Select State']+$stateLists, NULL , ['required','class' => 'form-control','title'=>'Please select state','id'=>'state-dropdown']) }}
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
            {{ Form::text('location',null, ['required','class'=>'parent_text form-control','id'=>'location','data-msg-required'=>'Please enter location','placeholder'=>"Enter Location",'autocomplete'=>'off']) }}
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
