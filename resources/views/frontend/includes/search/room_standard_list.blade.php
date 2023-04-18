@php
$selected = request()->get('room_standard') ? request()->get('room_standard'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>Room Type</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    <ul>
        @foreach($roomStandardFilter as $key => $value)
        @php
        $checked = in_array($key,explode(',',request()->get('room_standard')))?$key:'';
        @endphp
        <li>
            <div class="bravo-checkbox">
                <label>
                    {{ Form::checkbox('room_standard[]',$key , $checked , ['class' => 'form-control']) }}
                    {{$value}}
                    <span class="checkmark"></span>
                </label>
            </div>
        </li>
        @endforeach
    </ul>
</div>