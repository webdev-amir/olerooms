<div class="item-title e-close">
  <h3>Property Furnished Type</h3>
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" style="display: none;">
  <ul>
    @foreach($furnitureType as $key=>$value)
    <li>
      <div class="bravo-checkbox radio">
        <label>
          <input type="radio" id="furniture_type_{{$key}}" name="furniture_type" value="{{$key}}" {{request()->get('furniture_type') == $key ?'checked':''}} />
          {{$value}}
        </label>
      </div>
    </li>
    @endforeach
  </ul>
</div>