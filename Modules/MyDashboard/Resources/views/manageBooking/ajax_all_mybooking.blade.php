<table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">ID</th>
         <th scope="col">Image</th>
         <th scope="col">Property Details</th>
         <th scope="col">Property Location</th>
         <th scope="col">Date</th>
         <th scope="col">No. of guest</th>
         <th scope="col">Amount</th>
         <th scope="col">Status</th>
         <th scope="col">Action</th>
      </tr>
   </thead>
   <tbody>
      @forelse($records as $key => $recordList)
      <tr>
         <td scope="row" class="text-center"> <span class="turncateText">{{ $recordList->id }}</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $recordList->Property->CoverImg }}" alt="{{$recordList->Property->property_name}}" onerror="this.src='{{onerrorReturnImage()}}'"></th>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1">{{$recordList->Property->property_name}} ({{$recordList->Property->property_code}})</p>
               <p class="font18 medium d-flex align-items-center mb-1">
                  ({{$recordList->code}}) <span class="tag-booking">{{ $recordList->Property->propertyType->name }}</span>
               </p>
            </div>
         </td>
         <td scope="row">
            <span class="turncateText wordbreak">
               <p>{{$recordList->Property->search_address}}</p>
            </span>
         </td>
         <td class="check-date">
            <p>Booking : {{display_date($recordList->created_at)}}</p>
            <p>Checkin : {{ display_date($recordList->check_in_date)}}</p>
            @if(isset($recordList->check_out_date))
            <p>Checkout : {{display_date($recordList->check_out_date)}}</p>
            @endif
         </td>
         <td class="check-date">
            <p> @if(in_array($recordList->Property->propertyType->slug,['hostel-pg','hostel-pg-one-day','homestay']))
               {{$recordList->BookingJsonData['guests']}} Guests
               @else
               {{$recordList->BookingJsonData['adult']}} Adults - {{$recordList->BookingJsonData['children']}} Children
               @endif
            </p>
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

         <td class="text-end"> <span class="white fz14 bold nunito bg-blue tbleBtn details-complete btext-{{$recordList->status}}">{{ucfirst($recordList->status)}}</span> </td>
         <td class="text-center">
            <div class="dropdown">
               <div data-toggle="dropdown"><img src="{{asset('images/actionVector.png')}}"></div>
               <span class="caret"></span>
               <ul class="dropdown-menu bookdrop">
                  <a href="{{route('customer.dashboard.mybookings.details',[$recordList->slug])}}">
                     <li><img src="{{asset('images/view.png')}}" class="view-icon">View Booking</li>
                  </a>
                  <a class="myproperty_modal_review" href="javascript:;" data-toggle="modal" data-property-id="{{$recordList->Property->id}}" data-booking-id="{{$recordList->id}}" data-target="#reviewProperty" data-url="{{route('customer.get.review-details')}}">
                     <li><img src="{{asset('images/rating2.png')}}" class="view-icon">Rate Property</li>
                  </a>
               </ul>
            </div>
         </td>
      </tr>
      @empty
      <tr class="text-center">
         <td colspan="6">
            No Bookings Found
         </td>
      </tr>
      @endforelse
   </tbody>
</table>
<div class="pull-right">
   {!! $records->appends(array('type' => $type))->links('front_dash_pagination') !!}
</div>