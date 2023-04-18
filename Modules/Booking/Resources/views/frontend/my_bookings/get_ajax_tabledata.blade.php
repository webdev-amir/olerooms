 <table class="table tableDesign m-0">
   <thead>
      <tr>
         <th scope="col" class="text-center">S.No</th>
          <th scope="col">Image</th>
          <th scope="col">Property Details</th>
          <th scope="col">Date</th>
          <th scope="col">Amount</th>
          <th scope="col">Status</th>
      </tr>
   </thead>
   <tbody>
      @if($records != '')
      @foreach ($records as $record)
      <tr>
         <td scope="row" class="text-center"> <span class="turncateText">1.</span> </td>
         <td scope="row"> <span class="turncateText"><img src="{{ $record->CoverImgThunbnail }}" alt="booking"></th>
         <td scope="row">
            <div class="turncateText">
               <p class="mb-1">{{$record->first_name}} (OLERJRGP0980)<span class="tag-booking">Hotel</span></p>
               <p class="font18 medium d-flex align-items-center mb-1"> #KIS1160 <a href="#" class="ml-2 mr-2 tableoutline_btn btn btn-outline-success">Auto Confirm</a></p>
               <a href="#" class="requestTo_review d-flex align-items-center font15 medium"> 
                  <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="27" height="27"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 17l-5.878 3.59 1.598-6.7-5.23-4.48 6.865-.55L12 2.5l2.645 6.36 6.866.55-5.231 4.48 1.598 6.7z" fill="rgba(255,153,0,1)"/></svg>
                  Request to Review</a>
            </div>
         </td>
         <td class="check-date">
            <p>Booking : {{$record->check_in_date}}</p>
            <p>Booking :{{$record->check_out_date}}</p>
            <p>Booking : 25/06/2022</p>
         </td>
         <td class="check-date">
            <p> {{numberformatWithCurrency($record->total)}} </p>
         </td>
         <td class="text-end"> <span class="white fz14 bold nunito bg-blue tbleBtn details-complete">{{$record->status}}</span> </td>
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
   {!! $records->links('front_dash_pagination') !!}
   </div>