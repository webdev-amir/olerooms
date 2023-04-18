<div class="rightPanel_inner steps" id="step_4" @if($sessionAllData==false) style="display: none;" @endif @if($sessionAllData && isset($sessionAllData->current_step) && $sessionAllData->current_step != 4) style="display: none;" @endif >
	@php
	if($sessionData){
	$propertyType = getPropertyTypeBySlug($sessionData->step_1->property_type);
	$propertyTypeName = $propertyType->name;
	}
	else{
	$propertyTypeName ="";
	}
	if($sessionData && isset($sessionData->step_4))
	{
	$formData = $sessionData->step_4;
	}
	else{
	$formData = [];
	}
	@endphp


	{!! Form::model($formData, ['method' => 'post','route' => ['manageProperty.storeProperty'],'class'=>'','id'=>'F_AddForthSteps']) !!}
	{{ Form::hidden('user_id',Auth::user()->id, []) }}
	{{ Form::hidden('step',4, ['id'=>'steps']) }}
	{{ Form::hidden('session_property_entry',NULL, ['id'=>'session_property_entry']) }}
	<nav aria-label="breadcrumb" class="mb-5">
		<ol class="breadcrumb  bg-transparent p-0">
			<li class="breadcrumb-item"><a href="{{route('vendor.dashboard')}}">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Add Property</li>
			<li class="breadcrumb-item active" aria-current="page" id="propertyTypeName">{{$propertyTypeName}}</li>
		</ol>
	</nav>
	<div class="btnrow_progress">
		<div class="d-flex justify-content-between">
			@include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_2",'dataTitleContent'=>"Property Images"])
			@include('property::frontend.manageProperty.steps.includes.continue-button',['dataIdSubmit'=>"AddForthSteps"])
		</div>
	</div>
	<div class="selectproperty_row fadeInUp animated3 delay1 selected">
		<h4> Details to receive payments </h4>
		<div class="detail_panel mt-1">
			<div class="radiobuttonRow mt-3 mb-3">
				<div class="d-flex align-items-center">
					<div class="form-check p-0 mr-4">
						<input class="mr-2 payment_type_property" type="radio" name="payment_type" checked id="upi" value="upi">
						<label for="upi1" class="mb0 font18 medium"> UPI </label>
					</div>
					<div class="form-check p-0 mr-4">
						<input class="mr-2 payment_type_property" type="radio" name="payment_type" id="cheque" value="cheque">
						<label for="upi" class="mb0 font18 medium"> Bank Details </label>
					</div>
				</div>
			</div>
			<div class="row cheque_div payment_cheque_upi">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Bank Name</label>
						<input type="text" value="" placeholder="Bank Name" name="bank_name" class="form-control cheque_related_input" data-msg-required="Please enter bank name" required>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Account holder name</label>
						<input type="text" value="" placeholder="Account holder name" name="holder_name" class="form-control cheque_related_input" data-msg-required="Please enter account holder name" required>
					</div>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Account Number</label>
						<input type="text" value="" placeholder="Account Number" name="account_number" class="form-control cheque_related_input numberonly" data-msg-required="Please enter account number" maxlength="18" required>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">IFSC Code</label>
						<input type="text" value="" placeholder="IFSC Code" name="ifsc_code" class="form-control cheque_related_input" data-msg-required="Please enter IFSC Code" required>
					</div>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Upload cancelled cheque photo </h4>
					<div class="form-group ermsg w-45">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="cancelled_cheque_files">
								<div class="uploadfileBtn">
									<input type="file" id="cancelled_cheque" class="onlyimageupload" data-uploadurl="{{route('property.mediaStore',[auth()->user()->id])}}" />
									{{ Form::hidden('cancelled_check_photo',null, ['required','id'=>'f_cancelled_cheque','class'=>'cheque_related_input','title'=>'Please upload cancellled cheque image.']) }}
									<i class="ri-upload-line d-block"></i>
									Cancelled cheque
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Passbook front page </h4>
					<div class="form-group ermsg w-45">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="passbook_front_files">
								<div class="uploadfileBtn">
									<input type="file" class="onlyimageupload" data-uploadurl="{{route('property.mediaStore',[auth()->user()->id])}}" id="passbook_front" />
									{{ Form::hidden('passbook_front_photo',null, ['required','id'=>'f_passbook_front','class'=>'cheque_related_input','title'=>'Please upload passbook front page image.']) }}
									<i class="ri-upload-line d-block"></i>
									Passbook Front Page
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row upi_div" style="display:none ;">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Enter UPI ID</label>
						<input type="text" data-msg-required="Please enter UPI ID" placeholder="Enter UPI ID" name="upi_id" class="form-control upi_related_input">
					</div>
				</div>
			</div>

			<div class="row upi_div payment_cheque_upi" style="display:none ;">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Upload QR Code Image </h4>
					<div class="form-group ermsg w-35">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="upi_qr_code_files">
								<div class="uploadfileBtn">
									<input type="file" id="upi_qr_code" class="onlyimageupload" data-uploadurl="{{route('property.mediaStore',[auth()->user()->id])}}" />
									{{ Form::hidden('upi_qr_code_image',null, ['id'=>'f_upi_qr_code','class'=>'upi_related_input','title'=>'Please upload QR code image.']) }}
									QR<br> Code
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row notes_div">
				<div class="col-sm-12 col-md-12 col-lg-6">
					<div class="specialNotes">
						<p class="grey font16 regular mb-1">Special Notes</p>
						<ul class="ml-3">
							<li>Tax inclues 30% with GST also</li>
							<li>Water/ Electricity Charges Extra</li>
							<li>OLE ROOMS makes money, only when you make money . There is no upfront payment for listing the
								property. We will make all the effort to find you a tenant and adjust our service charges only after finalizing
								a tenant for you.</li>
							<li>OLE ROOMS does background verification and provides verified tenants.</li>
							<li>OLEROOMS guarantees on time rent payment is made to you.</li>
							<li>OLE ROOMS ensures your property is kept in good condition.</li>
							<li>OLE ROOMS does not charge any brokerage fee.</li>
							<li>OLE ROOMS charge a monthly commission of 20% of the rent (plus GST) ON FIRST MONTH RENTAL.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="btnrow_progress pb-0">
		<div class="progress">
			<div class="progress-bar" role="progressbar" style="width:90%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<div class="d-flex justify-content-between">
			@include('property::frontend.manageProperty.steps.includes.back-button',['dataBlockId'=>"step_3",'dataTitleContent'=>"Payment Details"])
			@include('property::frontend.manageProperty.steps.includes.continue-button-main',['dataStepNo'=>"4", 'dataIdSubmit'=>"AddForthSteps", 'dataLoaderContent'=>"Saving Property Payment Details",'dataTitleContent'=>""])
		</div>
	</div>
	{!! Form::close() !!}
</div>