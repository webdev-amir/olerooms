<table class="table table-bordered table-hover table-striped dataTable no-footer" id="data_filter">
    <thead>
      <tr>
         <th scope="col">#ID</th>
         <th scope="col">Property Details</th>
         <th scope="col">Customer Info</th>
         <th scope="col">Booking ID</th>
         <th scope="col">Status</th>
         <th scope="col">Visit Date & Time</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
          @foreach($records as $key => $list)
          @if(count($list->scheduleVisitProperty)>0)
          <tr class="myvisitRow" data-href="{{route('vendor.dashboard.myvisit.details', [$list->slug])}}">
            <td scope="row"> <span class="turncateText">{{$list->id}}</span></td>
            <td scope="row">
                <span class="turncateText">
                   @foreach($list->scheduleVisitProperty as $visitPropertyList)
                   {{ucfirst($visitPropertyList->property->property_name)}} ({{$visitPropertyList->property->property_code}})<br>
                   @endforeach
                </span>
            </td>
            <td scope="row">
                {{$list->customer->name}}
                <br>
                {{$list->customer->email}}
                <br>
                {{$list->customer->phone}}
            </td>
            <td scope="row">{{ isset($list->schedule_code) ? '#'.$list->schedule_code : 'N/A' }}</td>
            <td scope="row">
                <span class="label {{$list->status == 'confirmed'?'label-success':'label-danger'}}">{{ucfirst($list->status)}}</span>
            </td>
            <td class="check-date">
                @foreach($list->scheduleVisitProperty as $vlist)
                {{$vlist->VisitingDateAndTime}}<br>
                @endforeach
            </td>
            <td>
                 <span class="margin-r-5"><a data-toggle="tooltip" class="" title="View" href="{{route('adminschedulevisit.details', [$list->slug])}}"><i class="fa fa-eye" aria-hidden="true"></i></a> </span>
            </td>
          </tr>
          @endif
          @endforeach
      @else
      <tr>
         <td colspan="7" align="center">@lang('menu.no_record_found')</td>
      </tr>
      @endif
   </tbody>
</table>
@if(isset($records))
<div class="pull-right">
    {!! $records->appends(request()->query())->links('pagination') !!}
</div>
@endif