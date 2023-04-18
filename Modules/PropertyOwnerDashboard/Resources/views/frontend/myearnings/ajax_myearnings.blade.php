<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Image</th>
         <th scope="col">Property Details</th>
         <th scope="col">Payment Date</th>
         <th scope="col">Transaction ID</th>
         <th scope="col">Amount</th>
         <th scope="col">Payment Mode</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
      @foreach($records as $key => $recordList)
      <tr>
         <td scope="row" class="text-center"> <span class="turncateText">{{ $recordList->id }}</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $recordList->Booking->property->CoverImg }}" alt="booking"></th>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1"><span class="tag-booking" style="margin-left: 0px;">{{ $recordList->Booking->property->propertyType->name }}</span> #({{$recordList->Booking->code }})</p>
               <p class="font18 medium d-flex align-items-center mb-1">Customer: {{ucfirst($recordList->user->FullName)}}</p>
               <p class="font18 medium d-flex align-items-center mb-1">{{ucfirst($recordList->Booking->property->property_name)}} @if($recordList->Booking->property->property_code)({{$recordList->Booking->property->property_code}})@endif</p>
            </div>
         </td>
         <td class="check-date">
            <p>{{get_date_week_month_name($recordList->created_at)}}</p>
         </td>
         <td class="check-date">
            <p> {{$recordList->transaction_id}} </p>
         </td>
         <td class="text-end">
            {{ numberformatWithCurrency($recordList->amount,2) }}
         </td> 
         <td class="text-end">
            {{ucfirst($recordList->method)}} Payment
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
