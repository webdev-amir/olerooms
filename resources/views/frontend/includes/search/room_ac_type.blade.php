@php
$selected = request()->get('room_ac_type')?request()->get('room_ac_type'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
  <h3>Room Type - AC/Non- AC*</h3>
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
  <ul>
    @foreach($roomTypeFilter as $key => $value)
    <li>
      <div class="bravo-checkbox radio">
        <label>
          <input type="radio" id="room_ac_type_{{$key}}" name="room_ac_type" value="{{$key}}" {{$selected == $key ?'checked':''}} />
          {{$value}}
        </label>
      </div>
    </li>
    @endforeach
  </ul>
</div>