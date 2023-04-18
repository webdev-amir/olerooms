<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col">ID</th>
         <th scope="col">Property Details</th>
         <th scope="col">Booking ID</th>
         <th scope="col">Booking Date</th>
         <th scope="col">Amount</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
         @foreach($records as $key => $list)
            <tr class="myvisitRow" data-href="{{route('company.dashboard.myvisit.details', [$list->slug])}}">
               <td scope="row"> <span class="turncateText">{{$list->id}}</span></td>
               <td scope="row">
                  <span class="turncateText">
                     @foreach($list->scheduleVisitProperty as $visitPropertyList)
                        {{ucfirst($visitPropertyList->property->property_name)}} ({{$visitPropertyList->property->property_code}})<br>
                     @endforeach
                  </span>
               </td>
               <td> {{ isset($list->schedule_code) ? '#'.$list->schedule_code : 'N/A' }} </td>
               <td class="check-date">
                  @foreach($list->scheduleVisitProperty as $vlist)
                     {{$vlist->VisitingDateAndTime}}<br>
                  @endforeach
               </td>
               <td> {{numberformatWithCurrency($list->total)}} </td>
            </tr>
         @endforeach
      @else
         <tr>
            <td colspan="9" align="center">@lang('menu.no_record_found')</td>
         </tr>   
      @endif  
   </tbody>
</table>
<div class="pull-right">
{!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>