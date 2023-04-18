<div class="bravo_filter">
  <form action="{{route('search')}}" class="bravo_form_filter bravo_form_filter_property" method="get">
    @if(in_array('property_type_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.property_type')
    </div>
    @endif
  </form>

  <form action="{{route('search')}}" class="bravo_form_filter bravo_form_filter_search_property">
    @php
    $default_order_by = config('custom.default_sort_by');
    $orderby = request()->get('orderby');
    $default_search_layout = config('custom.default_search_layout');
    $search_layout = request()->get('searchLayout');
    $check_in_date = request()->get('check_in_date');
    $check_out_date = request()->get('check_out_date');
    $occupancy_id = request()->get('occupancy_id');
    $guests = request()->get('guests');
    $children = request()->get('children');
    $adults = request()->get('adults');
    @endphp

    <input type="hidden" value="{{$orderby ?? $default_order_by}}" name="orderby" id="propertyOrderBy">
    <input type="hidden" value="{{$search_layout ?? $default_search_layout}}" name="searchLayout" id="searchLayout">
    <input type="hidden" value="" name="map_value" id="mapShowVal">
    <input type="hidden" value="{{$check_in_date ?? ""}}" name="check_in_date" id="check_in_date">
    <input type="hidden" value="{{$check_out_date ?? ""}}" name="check_out_date" id="check_out_date">
    <input type="hidden" value="{{$occupancy_id ?? ""}}" name="occupancy_id" id="occupancy_id">
    <input type="hidden" value="{{$guests ?? ""}}" name="guests" id="guests" >
    <input type="hidden" value="{{$children ?? ""}}" name="children" id="children">
    <input type="hidden" value="{{$adults ?? ""}}" name="adults" id="adults">


    @if(in_array('state_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.state_list')
    </div>
    @endif

    @if(in_array('city_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.city_list')
    </div>
    @endif

    @if(in_array('location_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.location_list')
    </div>
    @endif

    {{--
      @if(in_array('bhk_type_filter',$allowedFilters))
      <div class="g-filter-item">
        @include('frontend.includes.search.bhk_type')
      </div>
      @endif
      --}}

    @if(in_array('flat_size_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.size_filter')
    </div>
    @endif

    @if(in_array('furniture_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.furniture_type')
    </div>
    @endif

    @if(in_array('available_for_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.available_for')
    </div>
    @endif

    @if(in_array('rating_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.rating')
    </div>
    @endif

    @if(in_array('price_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.price_range')
    </div>
    @endif

    @if(in_array('occupancy_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.occupancy_list')
    </div>
    @endif

    @if(in_array('room_ac_type_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.room_ac_type')
    </div>
    @endif

    @if(in_array('room_standard_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.room_standard_list')
    </div>
    @endif

    @if(in_array('guest_capacity_filter',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.guest_adult')
    </div>
    @endif
    {{--
    @if(in_array('guests_capacity',$allowedFilters))
    <div class="g-filter-item">
      @include('frontend.includes.search.capacity')
    </div>
    @endif--}}
  </form>
</div>