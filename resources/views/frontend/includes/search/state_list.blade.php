@php
$selected = request()->get('state_id')?request()->get('state_id'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>State</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    {{ Form::select('state_id', [''=>'Select State']+$stateListFilter, $selected , ['required','class' => 'form-control','title'=>'Please select state','id'=>'state-dropdown-filter','data-show-subtext'=>'true','data-live-search'=>'true']) }}
</div>