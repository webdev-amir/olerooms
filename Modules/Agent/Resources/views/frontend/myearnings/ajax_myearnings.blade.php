<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Image</th>
         <th scope="col">Property Details</th>
         <th scope="col">Credit Date</th>
         <th scope="col">Amount</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
      @foreach($records as $key => $recordList)
      <tr>
         <td scope="row" class="text-center"> <span class="turncateText">{{ $recordList->id }}</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $recordList->booking->property->CoverImg }}" alt="booking"></th>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1"><span class="tag-booking" style="margin-left: 0px;">{{ $recordList->booking->property->propertyType->name }}</span> #({{$recordList->booking->code }})</p>
               <p class="font18 medium d-flex align-items-center mb-1">Customer: {{ucfirst($recordList->user->FullName)}}</p>
               <p class="font18 medium d-flex align-items-center mb-1">{{ucfirst($recordList->booking->property->property_name)}} @if($recordList->booking->property->property_code)({{$recordList->booking->property->property_code}})@endif</p>
            </div>
         </td>
         <td class="check-date">
            <p>{{get_date_week_month_name($recordList->created_at)}}</p>
         </td>
         <td class="text-end">
            {{ numberformatWithCurrency($recordList->amount,2) }}
         </td> 
      </tr>
      @endforeach
      @else
      <tr class="text-center">
         <td colspan="7">
            No Record Found
         </td>
      </tr>
      @endif
   </tbody>
</table>
<div class="pull-right">
   {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>
