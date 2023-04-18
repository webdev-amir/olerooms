@php
$selected = request()->get('available_for') ? request()->get('available_for'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
  <h3>Available For</h3>
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
  <ul>
    @foreach($availableForFilter as $key => $value)
    @php
    $checked = in_array($key,explode(',',request()->get('available_for'))) ||in_array($key,explode(',',request()->get('available_for_type'))) ? $key : '';
    @endphp
    <li>
      <div class="bravo-checkbox">
        <label>
          {{ Form::checkbox('available_for[]',$key , $checked , ['class' => 'form-control occupancy_type_filter']) }}
          {{$value}}
          <span class="checkmark"></span>
          <!-- <input type="radio" id="available_for_{{$key}}" name="available_for" value="{{$key}}" {{ $key == $selected ?'checked':''}} />
          {{$value}} -->
        </label>
      </div>
    </li>
    @endforeach
  </ul>
</div>