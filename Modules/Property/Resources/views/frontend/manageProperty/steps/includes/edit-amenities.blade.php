<h4> Select Amenities </h4>
<div class="selectAmenities mt-3 mb-3 ermsg">
    @forelse($amenitiesData as $aList)
    @if($loop->iteration < 6) 
    <div class="form-check p-0">
        {!! Form::checkbox('amenities_id[]', $aList->id, null, ['required','class' => 'mr-2','id' => "amenities_id_$aList->id",'title'=>'Please select amenities']) !!}
        <label for="amenities_id_{{$aList->id}}" class="mb0">
            <img src="{{$aList->PicturePath}}" alt="{{ucfirst($aList->name)}}" onerror="this.src='{{onerrorReturnImage()}}'" />
            {{ucfirst($aList->name)}}
        </label>
    </div>
@else
<div class="form-check p-0 moreAmenites" style="display: none;">
    {!! Form::checkbox('amenities_id[]', $aList->id, null, ['required','class' => 'mr-2','id' => "amenities_id_$aList->id",'title'=>'Please select amenities']) !!}
    <label for="amenities_id_{{$aList->id}}" class="mb0">
        <img src="{{$aList->PicturePath}}" alt="{{ucfirst($aList->name)}}" onerror="this.src='{{onerrorReturnImage()}}'" />
        {{ucfirst($aList->name)}}
    </label>
</div>
@endif
@empty
@endforelse
<button type="button" class="btn btn-success" id="myBtn">Show More</button>
</div>