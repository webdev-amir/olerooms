@php
$selected = request()->get('occupancy_type') || request()->get('occupancy_id')??'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>Occupancy</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    <ul>
        @foreach($occupancyFilter as $key => $value)
        @php
        $checked = in_array($key,explode(',',request()->get('occupancy_type'))) ||in_array($key,explode(',',request()->get('occupancy_id'))) ? $key : '';
        @endphp
        <li>
            <div class="bravo-checkbox">
                <label>
                    {{ Form::checkbox('occupancy_type[]',$key , $checked , ['class' => 'form-control occupancy_type_filter']) }}
                    {{$value}}
                    <span class="checkmark"></span>
                </label>
            </div>
        </li>
        @endforeach
    </ul>
</div>