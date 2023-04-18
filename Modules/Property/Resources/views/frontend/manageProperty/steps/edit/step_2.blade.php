<div class="rightPanel_inner steps" id="step_2" style="display:none">
      @if($formData->propertyType->slug =='flat' )
      @include('property::frontend.manageProperty.steps.edit.step2includes.flat_step2')
      @elseif($formData->propertyType->slug =='homestay' )
      @include('property::frontend.manageProperty.steps.edit.step2includes.homestay_step2')
      @elseif($formData->propertyType->slug =='guest-hotel' )
      @include('property::frontend.manageProperty.steps.edit.step2includes.hotel_step2')
      @elseif($formData->propertyType->slug =='hostel-pg' )
      @include('property::frontend.manageProperty.steps.edit.step2includes.hostel_pg_step2')
      @else
      @include('property::frontend.manageProperty.steps.edit.step2includes.hostel_pg_one_step2')
      @endif
</div>