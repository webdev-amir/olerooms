@php
$selected = request()->get('area_id')?request()->get('area_id'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>Location</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none" @else style="display: block" @endif>
    <select name="area_id" class="form-control" data-show-subtext="true" data-live-search="true" id="area-dropdown-filter" title="Please select area">
        <option value="">Select Location</option>
    </select>
</div>