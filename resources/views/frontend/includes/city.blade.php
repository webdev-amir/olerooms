<div class="form-group">
    <div class="form-content">
	
        <?php
        $city_name = "";
        $list_json = [];
		
        foreach ($cityData as $list) {
			
            if (Request::query('city_id') == $list->id) {
                $city_name = $list->name;
            }
            $list_json[] = [
                'id' => $list->id,
                'title' => $list->name,
            ];
        }
        ?>
        <div class="smart-search searchcity">
            <input type="text" class="smart-search-city parent_text form-control" readonly placeholder="{{__("Search by city")}}" value="{{ $city_name }}" data-onLoad="{{__("Loading...")}}" data-default="{{json_encode($list_json)}}">
            <input type="hidden" class="child_id" name="city_id" value="{{Request::query('city_id')}}">
        </div>
    </div>
</div>