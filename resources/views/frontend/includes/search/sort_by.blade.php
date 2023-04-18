<span class="dropdown-toggle shorting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Sort By
</span>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" x-placement="bottom-end" style="
        position: absolute;
        will-change: transform;
        top: 0px;
        left: 0px;
        transform: translate3d(-253px, 29px, 0px);
    ">

    @foreach($sortBy as $key => $value)
    <a class="dropdown-item property-sort" href="javascript:void(0);" title="{{$key}}">
        {{$value}}
        @if(request()->get('orderby')==$key)
        <i class="fa fa-check float-right"></i>
        @endif
    </a>
    @endforeach
</div>