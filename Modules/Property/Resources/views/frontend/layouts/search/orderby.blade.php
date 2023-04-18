<div class="clear-filters">
    @php
	$clear_param = array();
	$param = request()->input();
	$param['_layout'] = 'map';
	$clear_param['address'] = request()->input('address');
	$clear_param['city'] = request()->input('city');
	$clear_param['long'] = request()->input('long');
	$clear_param['lat'] = request()->input('lat');
	$clear_param['start'] = request()->input('start');
	$clear_param['end'] = request()->input('end');
	$clear_param['date'] = request()->input('date');
	/*$clear_param['storage_id'] = request()->input('storage_id');*/
    @endphp
   <a href="{{ route('space',$param) }}">Location icon</a>
</div>&nbsp;&nbsp;
<div class="clear-filters">
   <a href="{{route('space',$clear_param)}}">
      Clear All Filter
   </a>
</div>