<div class="rightPanel_inner steps" id="step_3" style="display:none">
	@if($formData->propertyType->slug =='flat' )
	@include('property::frontend.manageProperty.steps.edit.step3includes.flat_step3')
	@elseif($formData->propertyType->slug =='homestay' )
	@include('property::frontend.manageProperty.steps.edit.step3includes.homestay_step3')
	@elseif($formData->propertyType->slug =='guest-hotel' )
	@include('property::frontend.manageProperty.steps.edit.step3includes.hotel_step3')
	@elseif($formData->propertyType->slug =='hostel-pg' )
	@include('property::frontend.manageProperty.steps.edit.step3includes.hostel_pg_step3')
	@else
	@include('property::frontend.manageProperty.steps.edit.step3includes.hostel_pg_one_step3')
	@endif	
</div>