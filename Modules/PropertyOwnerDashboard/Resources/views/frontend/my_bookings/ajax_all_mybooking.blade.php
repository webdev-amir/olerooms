<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Image</th>
         <th scope="col">Property Details</th>
         <th scope="col">Booked By</th>
         <th scope="col">Guest Details</th>
         <th scope="col">Date</th>
         <th scope="col">Amount</th>
         <th scope="col">Status</th>
      </tr>
   </thead>
   <tbody>
      @if(isset($records) && count($records) > 0)
      @foreach($records as $key => $recordList)
      <tr class="myvisitRow" data-href="{{route('vendor.dashboard.mybookings.details',[$recordList->slug])}}">
         <td scope="row" class="text-center"> <span class="turncateText">{{ $recordList->id }}</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $recordList->Property->CoverImg }}" alt="booking"></th>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1">{{ucfirst($recordList->Property->property_name)}} @if($recordList->Property->property_code)({{$recordList->Property->property_code}})@endif<span class="tag-booking">{{ $recordList->Property->propertyType->name }}</span></p>
               <p class="font18 medium d-flex align-items-center mb-1">({{$recordList->code }})
                  @if($recordList->booking_reject_confirm_type == $recordList::AUTOCONFIRMED)
                  <a href="javascript:;" class="ml-2 mr-2 tableoutline_btn btn btn-outline-success">Auto Confirmed</a>
                  @elseif($recordList->booking_reject_confirm_type == $recordList::AUTOREJECTED)
                  <a href="javascript:;" class="ml-2 mr-2 tableoutline_btn btn btn-outline-danger">Auto Rejected</a>
                  @endif
               </p>
               {{--
                  @if($recordList->status == 'completed')
               <a href="#" class="requestTo_review d-flex align-items-center font15 medium">
                  <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="27" height="27">
                     <path fill="none" d="M0 0h24v24H0z" />
                     <path d="M12 17l-5.878 3.59 1.598-6.7-5.23-4.48 6.865-.55L12 2.5l2.645 6.36 6.866.55-5.231 4.48 1.598 6.7z" fill="rgba(255,153,0,1)" />
                  </svg>
                  Request to Review</a>
               @endif 
               --}}
            </div>
         </td>

         <td>
            {{ucfirst($recordList->booked_by)}}
         </td>
         <td class="check-date">
            <p>{{$recordList->user->name}}</p>
            <p>{{$recordList->user->email}}</p>
            <p>{{$recordList->user->phone}}</p>
            <p>
               @if($recordList->booked_by =='company')
               {{$recordList->BookingJsonData['total_guests']??0}} Guests
               @else
               @if(in_array($recordList->Property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
               {{$recordList->BookingJsonData['guests']}} Guests
               @else
               {{$recordList->BookingJsonData['adult']}} Adults - {{$recordList->BookingJsonData['children']}} Children
               @endif
               @endif
            </p>
         </td>
         <td class="check-date">
            <p>Booking : {{display_date($recordList->created_at)}}</p>
            <p>Checkin : {{ display_date($recordList->check_in_date)}}</p>
            @if(isset($recordList->check_out_date))
            <p>Checkout : {{display_date($recordList->check_out_date)}}</p>
            @endif
         </td>
         <td class="check-date">
            <p>
               Paid : <span class="green">{{numberformatWithCurrency($recordList->total)}}
               </span>
            </p>
            @if($recordList->remaining_payable_amount>0)
            <p>
               Remaining : <span class="red">
                  {{numberformatWithCurrency($recordList->remaining_payable_amount)}}
               </span>
            </p>
            @endif
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
      @endforeach
      @else
      <tr class="text-center">
         <td colspan="6">
            No Record Found
         </td>
      </tr>
      @endif
   </tbody>
</table>
<div class="pull-right">
   {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>