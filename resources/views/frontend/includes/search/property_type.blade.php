@php
$selected = request()->get('property_type') ? request()->get('property_type') :'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>Category</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none" @else style="display: block" @endif>
    <ul>
        @foreach($propertyTypeFilter as $propertyType)
        <li>
            <div class="bravo-checkbox radio">
                <label>
                    <input type="radio" id="property_type_{{$propertyType->id}}" name="property_type" value="{{$propertyType->id}}" {{request()->get('property_type') == $propertyType->id ?'checked':''}} /> {{$propertyType->name}}
                </label>
            </div>
        </li>
        @endforeach
    </ul>
</div>