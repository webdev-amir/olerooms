<div class="form-group">
    <div class="form-content">
        <div class="smart-search searchcity" id="auto_com_search_div" data-searchroute="{{route('property.getAutocompleteLocationsLists')}}">
            <input type="text" class="autocomplete-search parent_text form-control" placeholder="Search by name, area and city" name="searchKey" value="{{request()->get('searchKey')}}" data-onLoad="Loading..." data-default="" id="autocom_search" autocomplete="off" data-property_type_id="{{request()->get('property_type')}}" > 
            <input type="hidden" class="child_id">
        </div>
    </div> 
</div>
