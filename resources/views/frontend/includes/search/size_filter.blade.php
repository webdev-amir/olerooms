<div class="item-title e-close">
    <h3>BHK</h3F>
        <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" style="display: none;">
    <ul>
        @foreach($availableSize as $key=>$value)
        <li>
            <div class="bravo-checkbox radio">
                <label>
                    <input type="radio" id="available_size_{{$key}}" name="available_size" value="{{$key}}" {{request()->get('available_size') == $key ?'checked':''}} />
                    {{$value}}
                </label>
            </div>
        </li>
        @endforeach
    </ul>
</div>