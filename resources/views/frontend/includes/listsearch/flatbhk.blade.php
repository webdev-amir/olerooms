<div class="form-group">
    <div class="form-content">
        <?php
        $bhk_name = "";
        $list_json = [];
        foreach ($bhkData as $key => $val) {
            if (Request::query('bhk_id') == $key) {
                $bhk_name = $val;
            }
            $list_json[] = [
                'id' => $key,
                'title' => $val,
            ];
        }
        ?>
        <div class="smart-search searchcity">
            <input type="text" class="smart-search-flatbhk parent_text form-control" readonly placeholder="{{__("BHK")}}" value="{{ $bhk_name }}" data-onLoad="{{__("Loading...")}}" data-default="{{json_encode($list_json)}}" name="bhk_type">
            <input type="hidden" class="child_id flatbhk" name="bhk_id" value="{{Request::query('bhk_id')}}">
        </div>
    </div>
</div>
<span class="flat-options" style="display:none;">Please choose flat type</span>