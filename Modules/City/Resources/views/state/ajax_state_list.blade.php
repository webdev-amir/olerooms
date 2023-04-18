<table class="table table-bordered table-hover table-striped dataTable no-footer" id="data_filter">
    <thead>
        <tr>
            <th>@lang('menu.sn')</th>
            <th>Country Name</th>
            <th>State Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($records) && count($records)>0)
        @foreach($records as $key => $list)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{ ucfirst($list->country->name) }}</td>
            <td>{{ ucfirst($list->name) }} </td>
            <td>
                @if($list->status == 0)
                    <span class="label label-danger">{{ucfirst('inActive')}}</span>
                @else
                    <span class="label label-success">{{ucfirst('active')}}</span>
                @endif
            </td>
            <td>
                @if($list->status==0) 
                    <a data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Active" rel="Active" name="{{route('state.status',$list->id)}}" href="javascript:;" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="{{route('state.status',$list->id)}}" data-refresh="no" data-reload="yes"><i class="fa fa-check" aria-hidden="true"></i></a>
                @else
                    <a data-toggle="tooltip" class="success tooltips"  title="Inactive"  rel="Inactive" name="{{route('state.status',$list->id)}}" href="javascript:;" data-placement="top" onClick="return AjaxActionTableDrow(this);" data-title="Inative" data-action="{{route('state.status',$list->id)}}" data-refresh="no" data-reload="yes"><i class="fa fa-ban" aria-hidden="true"></i></a>
                @endif
                <span class="margin-r-5"><a data-toggle="tooltip"  class="" title="Edit" href="{{ route('state.edit',[$list->id]) }}">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i></a> </span>
               <span class="margin-r-5"><a data-toggle="tooltip" class="" title="City" href="{{route('state.city', [$list->id])}}">&nbsp;<i class="fa fa-home" aria-hidden="true"></i></a> </span>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@if(isset($records))
<div class="pull-right">
    {!! $records->links('pagination') !!}
</div>
@endif