<div class="item-title e-close">
  <h3> Filter By Rating</h3>
  <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" style="display: none;">
  <ul>
    @foreach($ratingFilter as $key => $value)
    <li>
      <div class="bravo-checkbox radio">
        <label>
          <input type="radio" id="rating_{{$key}}" name="rating" value="{{$key}}" {{request()->get('rating') == $key ?'checked':''}} />
          {{$value}}
        </label>
      </div>
    </li>
    @endforeach
  </ul>
</div>