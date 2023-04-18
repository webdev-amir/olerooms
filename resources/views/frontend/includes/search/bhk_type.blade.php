@php
$selected = request()->get('bhk_type') ? request()->get('bhk_type'):'';
@endphp
<div class="item-title @if(!$selected) e-close @endif">
    <h3>BHK</h3>
    <i class="fa fa-angle-up" aria-hidden="true"></i>
</div>
<div class="item-content" @if(!$selected) style="display: none"@else style="display: block"  @endif>
    <ul>
        @foreach($bhkTypeFilter as $key => $value)
        @php
        $checked = in_array($key,explode(',',request()->get('bhk_type')))?$key:'';
        @endphp
        <li>
            <div class="bravo-checkbox">
                <label>
                    {{ Form::checkbox('bhk_type[]',$key , $checked , ['class' => 'form-control bhk_type_filter']) }}
                    {{$value}}
                    <span class="checkmark"></span>
                </label>
            </div>
        </li>
        @endforeach
    </ul>
</div>