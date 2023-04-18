<table class="table table-bordered table-hover table-striped dataTable no-footer" id="data_filter">
    <thead>
        <tr>
            <th>@lang('menu.sn')</th>
            <th>Area Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
            @foreach($records as $list)
            <tr>
                <td>{{$list->id}}</td>
                <td>{{$list->name}}</td>                
                @if($list->status == 0)
                    <td><span class="label label-danger">{{ trans('menu.inactive') }}</span></td>
                @else
                    <td><span class="label label-success">{{trans('menu.active')}}</span></td>
                @endif
                <td>
                 @if($list->status==0) 
                    <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Active" rel="Active" name="{{ route('state.city.area.status',[$state->id,$city->id,$list->slug])}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="{{route('state.city.area.status',[$state->id,$city->id,$list->slug])}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                 @else
                     <a data-toggle="tooltip" class="success tooltips"  title="Inactive"  rel="Inactive" name="{{ route('state.city.area.status',[$state->id,$city->id,$list->slug])}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Inative" data-action="{{ route('state.city.area.status',[$state->id,$city->id,$list->slug])}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                 @endif
                    <span class="margin-r-5"><a data-toggle="tooltip"  class="" title="Edit" href="{{ route('state.area.edit',[$list->city->state->id,$list->city_id, $list->slug]) }}">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i></a> </span>  
                    <span class="margin-r-5"><a href="javascript:;" data-toggle="tooltip" title="Delete" data-title="Delete" type="button"  data-placement="top" class="delete_ajax tble_button_st tooltips"  data-action="{{ route('state.city.area.destroy',[$state->id,$list->city_id,$list->slug]) }} " onClick="return AjaxActionTableDrow(this);" data-reload="yes"><i class="fa fa-trash-o" title="Delete"></i></a></span>
                </td>
            </tr>
            @endforeach
        @else
        <tr class="text-center">
            <td colspan="4">No Record Found</td>
        </tr>
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
    {!! $records->links('pagination') !!}
</div>
@endif