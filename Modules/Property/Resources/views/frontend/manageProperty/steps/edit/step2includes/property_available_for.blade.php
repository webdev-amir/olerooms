<div class="selectAmenities mt-3 mb-3 ermsg">
    @foreach(config::get('custom.property_available_for') as $lkey => $lVal)
        <div class="form-check p-0">
            {!! Form::checkbox('available_fors[]', $lkey, null, ['required','class' => 'mr-2','id' => "available_for_$lkey",'title'=>'Please select available type']) !!}
            <label for="available_for_{{$lkey}}" class="mb0">
                <img src="{{URL::to('images/'.$lkey.'.svg')}}" title="{{ucfirst($lVal)}}" />
                {{ucfirst($lVal)}}
            </label>
        </div>
    @endforeach
</div>
