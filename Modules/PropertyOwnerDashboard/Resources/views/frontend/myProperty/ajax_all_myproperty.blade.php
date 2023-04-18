<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Image</th>
         <th scope="col">Property Details</th>
         <th scope="col">City</th>
         <th scope="col">Seats</th>
         <th scope="col">Amount</th>
         <th scope="col">Selfie Status</th>
         <th scope="col">Agreement Status</th>
         <th scope="col">Publish</th>
         <th scope="col"> Action</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
      @foreach($records as $key => $recordList)
      @php
      if($recordList->status_selfie == 'approved'){
      $statusSelfieClass = 'details-complete';
      $statusSelfie = 'Approved';
      }
      elseif($recordList->status_selfie == 'pending'){
      $statusSelfieClass = 'details-pending';
      $statusSelfie = 'Pending Approval';
      }
      elseif($recordList->status_selfie == 'rejected'){
      $statusSelfieClass = 'details-cancle';
      $statusSelfie = 'Rejected';
      }
      else{
      $statusSelfieClass = 'details-not-uploaded';
      $statusSelfie = 'Not Uploaded';
      }

      if($recordList->status_agreement == 'approved'){
      $statusAgreementClass = 'details-complete';
      $statusAgreement = 'Approved';
      }
      elseif($recordList->status_agreement == 'pending'){
      $statusAgreementClass = 'details-pending';
      $statusAgreement = 'Pending Approval';
      }
      elseif($recordList->status_agreement == 'rejected'){
      $statusAgreementClass = 'details-cancle';
      $statusAgreement = 'Rejected';
      }
      else{
      $statusAgreementClass = 'details-not-uploaded';
      $statusAgreement = 'Not Uploaded';
      }
      @endphp
      <tr>
         <td scope="row" class="text-center"> <span class="turncateText">{{$recordList->id}}</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $recordList->CoverImgThunbnail }}" alt="booking"></td>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1 d-flex align-items-center"> <i class="ri-checkbox-circle-fill green mr-2 font20"></i> {{ucfirst($recordList->property_name)}}<span class="tag-booking">{{ucfirst($recordList->propertyType->name)}}</span></p>
               <p class="font18 medium d-flex align-items-center mb-1"> @if($recordList->property_code){{$recordList->property_code}} @else N/A @endif</p>
               <?php /*
	               <a href="#" class="requestTo_review d-flex align-items-center font15 medium"> 
	                  <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="27" height="27"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 17l-5.878 3.59 1.598-6.7-5.23-4.48 6.865-.55L12 2.5l2.645 6.36 6.866.55-5.231 4.48 1.598 6.7z" fill="rgba(255,153,0,1)"/></svg>
	                  Request to Review</a>
	                  */ ?>
            </div>
         </td>
         <td>
            <p> {{$recordList->city->name}} </p>
         </td>
         <td>
            @php
            if($recordList->total_seats !=NULL && $recordList->rented_seats!=NULL){
            $rooms = $recordList->total_seats.'/'.$recordList->rented_seats;
            }elseif($recordList->bhk_type !=NULL){
            $rooms = strtoupper($recordList->bhk_type);
            }else{
            $rooms = $recordList->rooms;
            }
            @endphp
            <p > {{$rooms}}
               <a class="black" href="{{route('manageProperty.edit',[$recordList->slug])}}">
                  <i class="ri-edit-2-line"></i>
               </a>
            </p>
         </td>
         <td class="check-date">
            <p> {{numberformatWithCurrency($recordList->PropertStartingAmount)}} </p>
         </td>
         <td class="text-end">
            <span class="white fz14 bold nunito bg-blue tbleBtn {{$statusSelfieClass}}">
               {{$statusSelfie}}</span><br><br>
            @if($statusSelfie != 'Approved')
            <a class="myproperty_modal selfi_agreement_button" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-target="#upload_selfie" data-remove="UploadSelfie"><i class="ri-camera-line"></i> Upload Selfie </a>
            @endif
         </td>
         <td class="text-end">
            <span class="white fz14 bold nunito bg-blue tbleBtn {{$statusAgreementClass}} ">
               {{$statusAgreement}}</span><br><br>
            @if($statusAgreement != 'Approved')
            <a class="selfi_agreement_button" href="{{ route('downloads3file') }}?fp={{$recordList->PropertyAgreementFromAdminDownloadPath}}" target="_blank"> <img src="{{asset('images/pdf-icon.svg') }}" alt="image" /> Agreement Sample </a>
            <br><br>
            @if($recordList->status_agreement)

            <a class="selfi_agreement_button" href="{{ route('downloads3file') }}?fp={{$recordList->MyPropertyAgreementDownloadPath}}" target="_blank"> <img src="{{asset('images/pdf-icon.svg') }}" alt="Download Agreement" /> Download Agreement </a> <br><br>
            @endif
            <a class="myproperty_modal selfi_agreement_button" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-target="#upload_agrement" data-remove="UploadAgreement"> <img src="{{asset('images/pdf-icon.svg') }}" alt="image" /> Upload Agreement</a>
            @endif
         </td>
         <td class="check-date">
            <div class="custom-control custom-switch">
               {{ Form::checkbox('is_publish', null, $recordList->is_publish, array('id'=>"switch$key",'class'=>'custom-control-input changestatus',
		               'data-id'=>"$recordList->id",'data-default'=>$recordList->is_publish,'data-title'=>"$recordList->ReversePublishTitle",'data-checked'=>1,'data-url'=>route('vendor.myproperty.status'))) }}
               <label class="custom-control-label" for="switch{{$key}}"></label>
            </div>
         </td>

         <td class="text-center">
            <div class="propertyoption_dropdown">
               <div data-toggle="dropdown" class="iconchange"><img class="iconimg" src="{{ asset('images/actionVector.png') }}"></div>
               <ul class="dropdown-menu bookdrop dropdown-menu-right">
                  {{-- @if($recordList->status_selfie != 'approved')
                        <a class="dropdown-item myproperty_modal" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-target="#upload_selfie"><i class="ri-camera-line"></i> Upload Selfie </a>
                  @endif
                  @if($recordList->status_agreement != 'approved')
                  <a class="dropdown-item myproperty_modal" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-target="#upload_agrement"> <img src="{{asset('images/pdf-icon.svg') }}" alt="image" /> Upload Agreement</a>
                  @endif --}}
                  @if($statusAgreement == 'Approved')
                  <a class="dropdown-item" href="{{ route('downloads3file') }}?fp={{$recordList->PropertyAgreementFromAdminDownloadPath}}" target="_blank"> <img src="{{asset('images/pdf-icon.svg') }}" alt="image" /> Download Agreement </a>
                  @endif

                  <a class="dropdown-item" href="{{route('manageProperty.show',$recordList->slug)}}" target="_blank"> <i class="ri-eye-line"></i> View Property</a>

                  <a class="dropdown-item myproperty_modal_offer" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-property-id="{{$recordList->propertyType->id}}" data-url="{{route('vendor.myproperty.offers') }}" data-offerapplyurl="{{route('vendor.myproperty.offerapply') }}" data-target="#applyOffer1"> <img src="{{asset('images/discount-ic.svg') }}" alt="image" /> Apply Offer </a>

                  <a class="dropdown-item" href="{{route('manageProperty.edit',[$recordList->slug])}}">
                     <i class="ri-edit-2-line"></i>Edit Property
                  </a>
                  <a class="dropdown-item myproperty_modal" href="javascript:;" data-toggle="modal" data-id="{{$recordList->id}}" data-target="#deleteProperty"> <i class="ri-delete-bin-line"></i> Delete Property </a>
               </ul>
            </div>
         </td>
      </tr>
      @endforeach
      @else
      <tr>
         <td colspan="9" align="center">@lang('menu.no_record_found')</td>
      </tr>
      @endif
   </tbody>
</table>
<div class="pull-right">
   {!! $records->links('front_dash_pagination') !!}
</div>