@foreach($incCofigRoomTypeArray as $roomType)
<div class="card mb-3 ermsg">
    <div class="card-header border-0 bg-transparent " id="{{$roomType}}room">
        <h2 class="mb-0">
            <button class="btn btn-link btn-block text-left pl-0 pr-0" type="button" data-toggle="collapse" data-target="#collapse{{$roomType}}room" aria-expanded="false" aria-controls="collapse{{$roomType}}">
                <label for="Oneroom" class="mb0 remembertext">
                    <input class="mr-2 room_type room_type_{{$roomType}}" type="checkbox" name="room_type[]" value="{{$roomType}}" title="Please select room type" {{in_array($roomType,(array)$formData['room_type'])  ?'checked':''}}> {{ucfirst($roomType)}} Room

                    @for($i=0; $i <$loop->iteration ; $i++ )
                        <img src="{{URL::to('images/bed.svg')}}" alt="Room Image" class="mr-1" />
                        @endfor
                        <span class="checkmark fcheckbox"></span>
                </label>
            </button>
        </h2>
    </div>
 
    <div id="collapse{{$roomType}}room" class="collapse" aria-labelledby="{{$roomType}}room" data-parent="#accordionExample">
        <div class="card-body ermsg">
            <div>
                <div class="d-flex justify-content-between mb-3">
                    <label for="{{$roomType}}AcRoomType" class="mb0 remembertext">
                        <input class="{{$roomType}}_room_input room_sub_type {{$roomType}}_room_sub_type" id="{{$roomType}}AcRoomType" type="checkbox" name="{{$roomType}}[is_ac]" title="Please select sub room type" data-room-type="{{$roomType}}" data-type="ac" value="1" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['is_ac'])  ?'checked':''}}>
                        A.C
                    </label>
                    <label for="{{$roomType}}-Ac-Inclusive-food" class="mb0 remembertext">
                        <input class="mr-2 {{$roomType}}_ac_is_food {{$roomType}}_room_input" type="checkbox" name="{{$roomType}}[ac_is_food_included]" id="{{$roomType}}-Ac-Inclusive-food" value="1" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['ac_is_food_included']) ?'checked':''}}  {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['is_ac']) ?'':'disabled'}}> Inclusive of food
                        
                        <span class="checkmark fcheckbox"></span>
                    </label>
                </div>
                <div class="row no-gutters mergeinput">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group ermsg">
                            <label>Total Seats</label>
                            <input type="text" placeholder="Total Seats" name="{{$roomType}}[ac_total_seats]" class="form-control border-radius-right-0 numberonly {{$roomType}}_ac_input {{$roomType}}_room_input" data-msg-required='Please enter total seats' {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['ac_total_seats'])  ?'required value ='.$formData[$roomType]['ac_total_seats']:'disabled'}}>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group centerborder ermsg">
                            <label>Available Seats</label>
                            <input type="text" placeholder="Available Seats" name="{{$roomType}}[ac_rented_seats]" class="form-control rounded-0 numberonly {{$roomType}}_ac_input {{$roomType}}_room_input" data-msg-required='Please enter available seats' {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['ac_rented_seats']) ? 'required value ='.$formData[$roomType]['ac_rented_seats']:'disabled'}}>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group ermsg">
                            <label>Amount per {{$amountTenure}}</label>
                            <input type="text" placeholder="Amount per {{$amountTenure}}" name="{{$roomType}}[ac_amount]" class="form-control border-radius-left-0 numberonly {{$roomType}}_ac_input {{$roomType}}_room_input" data-msg-required='Please enter amount' pattern="\d*" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['ac_amount']) ? 'required value ='.$formData[$roomType]['ac_amount']:'disabled'}}>
                            <p class="image-hint {{$roomType}}_room_ac_com" style="display:none;">{{@$formData->propertyType->AdminCommissionText}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="hrline mt-0" />
            <div>
                <div class="d-flex justify-content-between mb-3">
                    <label for="{{$roomType}}NonAcRoomType" class="mb0 remembertext">
                        <input class="mr-2 room_sub_type {{$roomType}}_room_input {{$roomType}}_room_sub_type" type="checkbox" name="{{$roomType}}[is_non_ac]" value="1" title="Please select room feature {{$roomType}}_room_input" id="{{$roomType}}NonAcRoomType" data-room-type="{{$roomType}}" data-type="non_ac" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['is_non_ac'])  ?'checked':''}}>
                        Non A.C
                    </label>
                    <label for="{{$roomType}}-NonAc-Inclusive-food" class="mb0 remembertext">
                        <input class="mr-2 {{$roomType}}_non_ac_is_food {{$roomType}}_room_input" type="checkbox" name="{{$roomType}}[non_ac_is_food_included]" id="{{$roomType}}-NonAc-Inclusive-food" value="1" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['non_ac_is_food_included'])  ?'checked':''}}  {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['is_non_ac']) ? '':'disabled'}}> Inclusive of food
                        <span class="checkmark fcheckbox"></span>
                    </label>
                </div>
                <div class="row no-gutters mergeinput">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group ermsg">
                            <label>Total Seats</label>
                            <input type="text" placeholder="Total Seats" name="{{$roomType}}[non_ac_total_seats]" class="form-control border-radius-right-0 numberonly {{$roomType}}_non_ac_input {{$roomType}}_room_input" data-msg-required='Please enter total seats' {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['non_ac_total_seats']) ? 'required value ='.$formData[$roomType]['non_ac_total_seats']:'disabled'}}>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group centerborder ermsg">
                            <label>Available Seats</label>
                            <input type="text" placeholder="Available Seats" name="{{$roomType}}[non_ac_rented_seats]" class="form-control rounded-0 numberonly {{$roomType}}_non_ac_input {{$roomType}}_room_input" data-msg-required='Please enter available seats' {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['non_ac_rented_seats']) ? 'required value ='.$formData[$roomType]['non_ac_rented_seats']:'disabled'}}>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group ermsg">
                            <label>Amount per {{$amountTenure}}</label>
                            <input type="text" placeholder="Amount per {{$amountTenure}}" name="{{$roomType}}[non_ac_amount]" class="form-control border-radius-left-0 numberonly {{$roomType}}_non_ac_input {{$roomType}}_room_input" data-msg-required='Please enter amount' pattern="\d*" {{in_array($roomType,(array)$formData['room_type']) && isset($formData[$roomType]['non_ac_amount']) ? 'required value ='.$formData[$roomType]['non_ac_amount']:'disabled'}}>
                            <p class="image-hint {{$roomType}}_room_non_ac_com" style="display:none;">{{@$formData->propertyType->AdminCommissionText}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach