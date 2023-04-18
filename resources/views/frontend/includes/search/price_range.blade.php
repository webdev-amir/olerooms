@php
$selected = request()->get('price_range') ? request()->get('price_range'):'';
@endphp

<div class="item-title @if(!$selected) e-close @endif">
  <h3>Price Range</h3>
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
  <ul>
    @foreach($priceFilter as $key => $value)
    <li>
      <div class="bravo-checkbox radio">
        <label>
          <input type="radio" id="price_range_{{$key}}" name="price_range" value="{{$key}}" {{request()->get('price_range') == $key ?'checked':''}} />
          {{$value}}
        </label>
      </div>
    </li>
    @endforeach
  </ul>
</div>