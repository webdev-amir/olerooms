@php
$default_search_layout = config('custom.default_search_layout');
$row_search_layout = config('custom.row_search_layout');
$search_layout = request()->get('searchLayout');
@endphp
<div class="bravo-list-item">
    <div class="topbar-search">
        <h2 class="text">
            {{ $rows->total() ? Str::plural('Property',$rows->total()) : "No "}}  <span class="details-code">{{$rows->total() ? $rows->total() : ""}}</span> {{Str::plural('result',$rows->total())}} 
            found 
        </h2>
        <div class="control-topsearch">
             <div class="mobileproperty_filter">
                <a href="javascript:;" ><img src="{{URL::to('images/filtter.png')}}" alt="IOS App"/></a>
            </div>
            <div class="item">
                <a href="javascript:;" class="search-layout" title="{{$default_search_layout ?? 'grid'}}"><i class="ri-grid-fill {{($search_layout == $default_search_layout)|| !$search_layout ?'active':''}}"></i></a>
            </div>
            <div class="item">
                <a href="javascript:;" class="search-layout" title="{{$row_search_layout ?? 'row'}}"><i class="ri-list-unordered {{($search_layout == $row_search_layout)?'active':''}}"></i></a>
            </div>
            <div class="item locationpin">
                <a href="javascript:;" class="property-map" data-title="show_map">
                    <i class="ri-map-pin-line"></i>
                </a>
            </div>
            <div class="dropdown">
                @include('frontend.includes.search.sort_by')
            </div>
        </div>
    </div>
    <div class="list-item search_properties_ {{($search_layout == $row_search_layout)?'grid-list':''}}">
        <div class="row">
            @forelse($rows as $row)
            @if(($search_layout == config('custom.default_search_layout'))||!$search_layout)
            @include('frontend.includes.search.loop_grid')
            @else
            @include('frontend.includes.search.loop_row')
            @endif
            @empty
            <div class="col-lg-12">
                <div class="list-item mt-0 pt-3">
                    <div class="_1sHuca"><img src="{{URL::to('img/noproperty-found.gif')}}" alt="No Property Found" title="No Property Found"><div class="_3uTeW4">Sorry, no results found!</div><div class="CqJpD_">Please check the spelling or try searching for something else</div></div>
                </div>
            </div>
            @endforelse
        </div>
        <div class="bravo-pagination">
            {{$rows->appends(request()->query())->links('pagination::bootstrap-4')}}
            @if($rows->total() > 0)
            <span class="count-string">{{ __("Showing :from - :to of :total ".Str::plural('Property',$rows->total()),["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}
            </span>
            @endif
        </div>
    </div>
</div>