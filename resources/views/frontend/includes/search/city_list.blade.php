@php
$selected = request()->get('city_id')?request()->get('city_id'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>City</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    <select name="city_id" class="form-control" data-show-subtext="true" data-live-search="true" title="Please select city" id="city-dropdown-filter">
        <option value="">Select City</option>
        @foreach($cityListFilter as $city)
        <option value="{{$city->id}}" {{$selected==$city->id?'selected':''}}>{{$city->name}}</option>
        @endforeach
    </select>
</div> 