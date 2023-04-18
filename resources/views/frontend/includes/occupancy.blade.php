<div class="form-group">
    <div class="form-content">
        <?php
        $occupancy_name = "";
        $list_json = [];
		
        foreach ($occupancyData as $key=>$val) {
			
            if (Request::query('occupancy_id') == $key) {
                $occupancy_name = $val;
            }
            $list_json[] = [
                'id' => $key,
                'title' => $val,
            ]; 
        }
        ?>
        <div class="smart-search searchcity">
            <input type="text" class="smart-search-occupancy parent_text form-control" readonly placeholder="{{__("Occupancy")}}" value="{{ $occupancy_name }}" data-onLoad="{{__("Loading...")}}" data-default="{{json_encode($list_json)}}">
            <input type="hidden" class="child_id" name="occupancy_id" value="{{Request::query('occupancy_id')}}">
        </div>
    </div>
</div>