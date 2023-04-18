<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col">ID</th>
         <th scope="col">Property Details</th>
         <th scope="col">Customer Name</th>
         <th scope="col">Visit ID</th>
         <th scope="col">Status</th>
         <th scope="col">Visit Date & Time</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
      @foreach($records as $key => $list)
      @if(count($list->scheduleVisitPropertyForVendor)>0)
      <tr class="myvisitRow" data-href="{{route('vendor.dashboard.myvisit.details', [$list->slug])}}">
         <td scope="row"> <span class="turncateText">{{$list->id}}</span></td>
         <td scope="row">
            <span class="turncateText">
               @foreach($list->scheduleVisitPropertyForVendor as $visitPropertyList)
               {{ucfirst($visitPropertyList->property->property_name)}} ({{$visitPropertyList->property->property_code}})<br>
               @endforeach
            </span>
         </td>
         <td scope="row">{{$list->customer->name}}</td>
         <td scope="row">{{ isset($list->schedule_code) ? '#'.$list->schedule_code : 'N/A' }}</td>
         <td scope="row">
            <span class="white fz14 bold nunito bg-blue tbleBtn details-complete btext-{{$list->status}}">
               {{ isset($list->status) ? ucfirst($list->status) : 'N/A' }}
            </span>
         </td>
         <td class="check-date">
            @foreach($list->scheduleVisitPropertyForVendor as $vlist)
            {{$vlist->VisitingDateAndTime}}<br>
            @endforeach
         </td>
      </tr>
      @endif
      @endforeach
      @else
      <tr>
         <td colspan="5" align="center">@lang('menu.no_record_found')</td>
      </tr>
      @endif
   </tbody>
</table>
<div class="pull-right">
   {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>