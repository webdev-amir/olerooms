<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Property Details</th>
         <th scope="col">Customer Details</th>
         <th scope="col">Date</th>
         <th scope="col">Total Amount</th>
         <th scope="col">Status</th>
      </tr>
   </thead>
   <tbody>

      @forelse($records as $key => $recordList)
      <tr class="myvisitRow" data-href="{{route('agent.dashboard.mybookings.details',[$recordList->slug])}}">
         <td scope="row" class="text-center"> <span class="turncateText">{{ $recordList->id }}</span> </td>
         <td scope="row">
            <div class="turncateText">
               <span class="turncateText"><img src="{{ $recordList->Property->CoverImg }}" alt="booking">
                  <p class="mb-1">
                     {{$recordList->Property->property_code}}
                     <span class="tag-booking">{{ $recordList->Property->propertyType->name }}</span>
                  </p>
                  <p class="font18 medium d-flex align-items-center mb-1">({{$recordList->code }})
                     @if($recordList->booking_reject_confirm_type == $recordList::AUTOCONFIRMED)
                     <a href="javascript:;" class="ml-2 mr-2 tableoutline_btn btn btn-outline-success">Auto Confirmed</a>
                     @elseif($recordList->booking_reject_confirm_type == $recordList::AUTOREJECTED)
                     <a href="javascript:;" class="ml-2 mr-2 tableoutline_btn btn btn-outline-danger">Auto Rejected</a>
                     @endif
                  </p>
            </div>
         </td>

         <td scope="row">
            <div class="turncateText">
               <span class="turncateText"><img src="{{ $recordList->user->PicturePath }}" alt="booking">
                  <p class="mb-1">{{ucfirst($recordList->user->name)}} <span class="tag-booking">{{ucfirst($recordList->booked_by)}}</span></p>
                  <p class="font18 medium d-flex align-items-center mb-1">
                     {{ucfirst($recordList->user->email)}}
                  </p>
            </div>
         </td>
         <td class="check-date">
            <p>Booking : {{display_date($recordList->created_at)}}</p>
            <p>Checkin : {{ display_date($recordList->check_in_date)}}</p>
            @if(isset($recordList->check_out_date))
            <p>Checkout : {{display_date($recordList->check_out_date)}}</p>
            @endif
         </td>
         <td class="check-date">
            <p>Total: {{numberformatWithCurrency($recordList->amount)}} </p>
            <p>Discounted: {{numberformatWithCurrency($recordList->final_offer_amount)}} </p>
            <p>My Earning: <span class="text-completed">{{numberformatWithCurrency($recordList->agent_corp_points)}} </p>
         </td>
         <td class="text-end">
            <span class="white fz14 bold nunito bg-blue tbleBtn details-complete btext-{{$recordList->StatusClass}}">{{booking_status_to_text($recordList->status)}}</span>
            @if($recordList->status=='cancelled' && isset($recordList->booking_cancelled_date))
            <br></br>
            <p>Cancelled Date : {{display_date($recordList->booking_cancelled_date)}}</p>
            @endif
            @if($recordList->status=='completed' && isset($recordList->booking_completed_date))
            <br></br>
            <p>Completed Date : {{display_date($recordList->booking_completed_date)}}</p>
            @endif
         </td>
      </tr>
      @empty
      <tr class="text-center">
         <td colspan="6">
            No Record Found
         </td>
      </tr>
      @endforelse

   </tbody>
</table>
<div class="pull-right">
   {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>