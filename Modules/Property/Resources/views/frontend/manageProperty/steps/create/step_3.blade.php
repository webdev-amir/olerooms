<div class="rightPanel_inner steps" id="step_3" @if($sessionAllData==false) style="display: none;" @endif @if($sessionAllData && isset($sessionAllData->current_step) && $sessionAllData->current_step != 3) style="display: none;" @endif >
	<div id="reserve_block3">
		@if((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='flat')
		@include('property::frontend.manageProperty.steps.create.step3includes.flat_step3')
		@elseif((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='homestay')
		@include('property::frontend.manageProperty.steps.create.step3includes.homestay_step3')
		@elseif((!empty($sessionData) && isset($sessionData->step_1))&& $sessionData->step_1->property_type =='guest-hotel' )
		@include('property::frontend.manageProperty.steps.create.step3includes.hotel_step3')
		@else
		@include('property::frontend.manageProperty.steps.create.step3includes.hostel_pg_step3')
		@endif
	</div>
</div>