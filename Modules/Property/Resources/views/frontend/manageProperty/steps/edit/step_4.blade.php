<div class="rightPanel_inner steps" id="step_4" style="display:none">

	@php
	$routeName = auth()->user()->hasRole('admin')? 'admin.property.mediaStore':'property.mediaStore';
	@endphp


	@if(auth()->user()->hasRole('admin'))
	{!! Form::model($formData->propertyPaymentInfo, ['method' => 'put','route' => ['admin.manageProperty.update',$formData->id],'class'=>'','id'=>'F_AddForthSteps']) !!}
	@else
	{!! Form::model($formData->propertyPaymentInfo, ['method' => 'put','route' => ['manageProperty.update',$formData->id],'class'=>'','id'=>'F_AddForthSteps']) !!}
	@endif
	{{ Form::hidden('user_id',Auth::user()->id, []) }}
	{{ Form::hidden('step',4, ['id'=>'steps']) }}
	<nav aria-label="breadcrumb" class="mb-5">
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
						<input class="mr-2 payment_type_property" type="radio" name="payment_type" id="upi" value="upi" {{@$formData->propertyPaymentInfo->payment_type == 'upi'?'checked':'' }}>
						<label for="upi1" class="mb0 font18 medium"> UPI </label>
					</div>
					<div class="form-check p-0 mr-4">
						<input class="mr-2 payment_type_property" type="radio" name="payment_type" id="cheque" value="cheque" {{@$formData->propertyPaymentInfo->payment_type == 'cheque'? 'checked':'' }}>
						<label for="upi" class="mb0 font18 medium"> Bank Details </label>
					</div>
				</div>
			</div>
			<div class="row cheque_div">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Bank Name</label>
						<input type="text" value="{{@$formData->propertyPaymentInfo->bank_name}}" placeholder="Bank Name" name="bank_name" data-default="{{@$formData->propertyPaymentInfo->bank_name}}" class="form-control cheque_related_input" data-msg-required="Please enter bank name" required>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Account holder name</label>
						<input type="text" value="{{@$formData->propertyPaymentInfo->holder_name}}" placeholder="Account holder name" name="holder_name" data-default="{{@$formData->propertyPaymentInfo->holder_name}}" class="form-control cheque_related_input" data-msg-required="Please enter account holder name" required>
					</div>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Account Number</label>
						<input type="text" value="{{@$formData->propertyPaymentInfo->account_number}}" placeholder="Account Number" name="account_number" data-default="{{@$formData->propertyPaymentInfo->account_number}}" class="form-control cheque_related_input numberonly" data-msg-required="Please enter account number" maxlength="18">
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">IFSC Code</label>
						<input type="text" value="{{@$formData->propertyPaymentInfo->ifsc_code}}" placeholder="IFSC Code" name="ifsc_code" data-default="{{@$formData->propertyPaymentInfo->ifsc_code}}" class="form-control cheque_related_input" data-msg-required="Please enter IFSC Code">
					</div>
				</div>

				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Upload cancelled cheque photo </h4>
					<div class="form-group ermsg w-50">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="cancelled_cheque_files">

								<div class="uploadfileBtn">
									<input type="file" id="cancelled_cheque" class="onlyimageupload" data-uploadurl="{{route($routeName,[$formData->user_id])}}" />
									{{ Form::hidden('cancelled_check_photo',@$formData->propertyPaymentInfo->cancelled_check_photo, ['id'=>'f_cancelled_cheque','class'=>'cheque_related_input cancelled_check_photo','title'=>'Please upload cancellled cheque image.', 'data-default'=>@$formData->propertyPaymentInfo->cancelled_check_photo]) }}
									<i class="ri-upload-line d-block"></i>
									Cancelled cheque
								</div>
							</div>
							@if(@$formData->propertyPaymentInfo->payment_type == 'cheque' && $formData->propertyPaymentInfo->CancleCheckPhotoThumbnail)
							<span class="pip payment_pip"><img class="imageThumb" src="{{@$formData->propertyPaymentInfo->CancleCheckPhotoThumbnail}}" title="Cancelled Check Image" /><br />
								<span class="remove removesingle" data-remove="cancelled_check_photo">Remove image</span></span>
							@endif
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Passbook front page </h4>
					<div class="form-group ermsg w-50">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="passbook_front_files">

								<div class="uploadfileBtn">
									<input type="file" class="onlyimageupload" data-uploadurl="{{route($routeName,[$formData->user_id])}}" id="passbook_front" />
									{{ Form::hidden('passbook_front_photo',@$formData->propertyPaymentInfo->passbook_front_photo, ['id'=>'f_passbook_front','class'=>'cheque_related_input passbook_front_photo','title'=>'Please upload passbook front page image.', 'data-default'=>@$formData->propertyPaymentInfo->passbook_front_photo ]) }}
									<i class="ri-upload-line d-block"></i>
									Passbook Front Page
								</div>
							</div>
							@if(@$formData->propertyPaymentInfo->payment_type == 'cheque' && $formData->propertyPaymentInfo->PassbookFrontPhotoThumbnail)
							<span class="pip payment_pip"><img class="imageThumb" src="{{@$formData->propertyPaymentInfo->PassbookFrontPhotoThumbnail}}" title="Cancelled Check Image" /><br />
								<span class="remove removesingle" data-remove="passbook_front_photo">Remove image</span></span>
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="row upi_div" style="display:none ;">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<div class="form-group ermsg">
						<label class="font16 grey">Enter UPI ID</label>
						<input type="text" value="{{@$formData->propertyPaymentInfo->upi_id}}" data-msg-required="Please enter UPI ID" placeholder="Enter UPI ID" name="upi_id" class="form-control upi_related_input" data-default="{{@$formData->propertyPaymentInfo->upi_id}}">
					</div>
				</div>
			</div>

			<div class="row upi_div" style="display:none ;">
				<div class="col-sm-12 col-md-6 col-lg-6">
					<h4 class="font18 black medium mb-2"> Upload QR Code Image </h4>
					<div class="form-group ermsg w-50">
						<div class="uploadImages_block">
							<div class="uploadSinglefile" id="upi_qr_code_files">

								<div class="uploadfileBtn">
									<input type="file" id="upi_qr_code" class="onlyimageupload" data-uploadurl="{{route($routeName,[@$formData->user_id])}}" />
									<i class="ri-upload-line d-block"></i>
									{{ Form::hidden('upi_qr_code_image',@$formData->propertyPaymentInfo->upi_qr_code_image, ['id'=>'f_upi_qr_code','class'=>'upi_related_input upi_qr_code_image', 'data-default'=>@$formData->propertyPaymentInfo->upi_qr_code_image,'title'=>'Please upload QR code image.']) }}
									QR<br> Code
								</div>
							</div>
							@if(@$formData->propertyPaymentInfo->payment_type == 'upi' && $formData->propertyPaymentInfo->QRCodeThumbnail)
							<span class="pip payment_pip"><img class="imageThumb" src="{{@$formData->propertyPaymentInfo->QRCodeThumbnail}}" title="QR CODE Image" /><br />
								<span class="remove removesingle" data-remove="upi_qr_code_image">Remove image</span></span>
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="row notes_div">
				<div class="col-sm-12 col-md-12 col-lg-6">
					<div class="specialNotes">
						<p class="grey font16 regular mb-1">Special Notes</p>
						<ul class="ml-3">
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