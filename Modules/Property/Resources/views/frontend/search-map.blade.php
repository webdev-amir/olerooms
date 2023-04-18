@extends('layouts.app')
@section('title',"Search ".trans('menu.pipe')." " .app_name())
@section('content')
<div class="page-template-content" id="BookNowButtonPage">
    <div class="bravo_search_event listing-track padding50 bgDark m-0 pb-0 selected">
        <div class="container">
            <div class="row listing-back">
                <div class="col-lg-12 col-md-12 listing-right">
                    <div class="bravo-list-item">
                        <div class="topbar-search border-0">
                            <h2 class="text">
                                {{Str::plural('Property',$rows->count())}} <span class="details-code">{{$rows->count()}}</span> {{Str::plural('result',$rows->count())}} found
                            </h2>
                            <div class="control-topsearch">
                                <div class="item">
                                    <a href="{{request()->fullUrlWithQuery(['map_value' => null,'searchLayout'=>'grid'])}}"><i class="ri-grid-fill"></i></a>
                                </div>
                                <div class="item">
                                    <div class="item-title">
                                       <a href="{{request()->fullUrlWithQuery(['map_value' => null,'searchLayout'=>'row'])}}"> <i class="ri-list-unordered"></i> </a>
                                </div>
                            </div>
                            <div class="item">
                                <a><i class="ri-map-pin-line active"></i></a>
                            </div>
                        </div>
                    </div>
                        @if($rows->count()>0)
                        <div class="list-item mt-0 pt-3">
                            <div id="bravo_results_map" class="mapProperty"></div>
                        </div>
                        @else
                        <div class="list-item mt-0 pt-3">
                            <div class="_1sHuca"><img src="{{URL::to('img/noproperty-found.gif')}}" alt="No Property Found" title="No Property Found"><div class="_3uTeW4">Sorry, no results found!</div><div class="CqJpD_">Please check the spelling or try searching for something else</div></div>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('uniquePageScript')
<script src="{!! Module::asset('property:js/property-map.js') !!}"></script>
<script>
    var bravo_map_data = {
        markers: {!!json_encode($markers) !!}
    };
</script>
@endsection