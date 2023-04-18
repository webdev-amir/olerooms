<table class="table table-bordered table-hover table-striped dataTable no-footer"  id="data_filter">
    <thead> 
        <tr>
            <th width="05%">ID</th>
            <th width="15%" data-orderable="false">City Name</th>
            <th width="10%">Image</th>
            <th width="15%">Status</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
            @foreach($records as $list)
            <tr>
                <td>{{$list->id}}</td>
                <td>{{$list->name}}</td>
                <td> <a href="{{$list->ThumbPicturePath}}" data-lightbox="example-1"><img class="" style="width: 60px;" src="{{$list->ThumbPicturePath}}"></a></td>
                @if($list->status == 0)
                    <td><span class="label label-danger">{{ trans('menu.inactive') }}</span></td>
                @elseif($list->status == 1)
                    <td><span class="label label-success">{{trans('menu.active')}}</span></td>
                @else
                    <td><span class="label label-warning">Coming Soon</span></td>
                @endif
                <td>
                <span class="margin-r-5"><a data-toggle="tooltip"  class="" title="Edit" href="{{ route('state.city.edit',['stateId'=>$state->id,'id'=>$list->id]) }}">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i></a> </span>
                <span class="margin-r-5"><a data-toggle="tooltip"  class="" title="Areas" href="{{ route('state.city.areas',[$state->id,$list->id])}}"><i class="fa fa-home" aria-hidden="true"></i></a> </span>  
                </td>
            </tr>
            @endforeach
        @else
        <tr class="text-center">
            <td colspan="5">No Record Found</td>
        </tr>
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
 {!! $records->links('pagination') !!} 
</div>
@endif