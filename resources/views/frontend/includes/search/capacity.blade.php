@php
$selected = request()->get('capacity')?request()->get('capacity'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>Capacity</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    {{ Form::select('capacity', [''=>'Select Capacity']+$capacityListFilter, $selected , ['class' => 'form-control']) }}
</div>