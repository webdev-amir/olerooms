<div class="rightPanel_inner steps" id="step_2" @if($sessionAllData==false) style="display: none;" @endif @if($sessionAllData && isset($sessionAllData->current_step) && $sessionAllData->current_step != 2) style="display: none;" @endif >
   <div id="reserve_block2">
      @if((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='flat' )
      @include('property::frontend.manageProperty.steps.create.step2includes.flat_step2')
      @elseif((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='homestay' )
      @include('property::frontend.manageProperty.steps.create.step2includes.homestay_step2')
      @elseif((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='guest-hotel' )
      @include('property::frontend.manageProperty.steps.create.step2includes.hotel_step2')
      @elseif((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='hostel-pg' )
      @include('property::frontend.manageProperty.steps.create.step2includes.hostel_pg_step2')
      @else
      @include('property::frontend.manageProperty.steps.create.step2includes.hostel_pg_one_step2')
      @endif
   </div>

</div>