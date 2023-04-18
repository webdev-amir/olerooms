@php
$selected = request()->get('rooms_capacity')?request()->get('rooms_capacity'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>No. of Rooms</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    {{ Form::select('rooms_capacity', [''=>'No. of Rooms']+$roomCapacityListFilter, $selected , ['class' => 'form-control']) }}
</div>